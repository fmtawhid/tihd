import videojs from 'video.js'
import 'videojs-contrib-ads'
import 'video.js/dist/video-js.css'
import 'videojs-youtube'

document.addEventListener('DOMContentLoaded', function () {
  const player = videojs('videoPlayer', {
    techOrder: ['vimeo', 'youtube', 'html5', 'hls'],
    autoplay: false,
    controls: true
  })

  const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content')
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
  let isVideoLoaded = false
  let currentVideoUrl = ''
  let isWatchHistorySaved = false

  async function CheckDeviceType() {
    try {
      const response = await fetch(`${baseUrl}/check-device-type`)
      const data = await response.json()
      return data.isDeviceSupported
    } catch (error) {
      return false
    }
  }

  async function checkAuthenticationAndDeviceSupport() {
    const isDeviceSupported = await CheckDeviceType()
    return isAuthenticated && isDeviceSupported
  }

  async function loadVideoIfAuthenticated() {
    const accessType = document.querySelector('#videoPlayer').getAttribute('data-movie-access')

    // if (!isAuthenticated) {
    //   return // Exit if not authenticated
    // }

    let canPlay = true
    if (accessType === 'paid') {
      canPlay = await checkAuthenticationAndDeviceSupport()
    }

    if (canPlay && !isVideoLoaded) {
      const encryptedData = document.querySelector('#videoPlayer').getAttribute('data-encrypted')

      fetch(`${baseUrl}/video/stream/${encodeURIComponent(encryptedData)}`)
        .then((response) => response.json())
        .then((data) => {
          const qualityOptions = data.qualityOptions
          setVideoSource(player, data.platform, data.videoId, data.url, data.mimeType, qualityOptions)
          player.load()
          player.one('loadedmetadata', async function () {
            player.currentTime(0)
            player.muted(true) // Mute the player for autoplay
            try {
              await player.play()
            } catch (error) {
              console.error('Error trying to autoplay:', error)
            }
          })
          isVideoLoaded = true
        })
        .catch((error) => console.error('Error fetching video:', error))
    }
   else{
    $('#DeviceSupport').modal('show')
  }
  }

  loadVideoIfAuthenticated()

  const playButton = document.querySelector('.vjs-big-play-button')
  if (playButton) {
    playButton.addEventListener('click', async function (e) {
      if (!isAuthenticated) {
        e.preventDefault() // Prevent play
        window.location.href = loginUrl // Redirect to login
      } else {
        const canPlay = await checkAuthenticationAndDeviceSupport()
        if (!canPlay) {
          e.preventDefault() // Prevent play if conditions are not met
        }
      }
    })
  }

  const handleWatchButtonClick = async (button, isSeasonWatch = false) => {
    const accessType = button.getAttribute('data-movie-access')
    const qualityOptionsData = button.getAttribute('data-quality-options')
    const qualityOptions = Object.entries(JSON.parse(qualityOptionsData)).map(([label, url]) => ({ label, url }))
    const videoUrl = button.getAttribute('data-video-url')
    currentVideoUrl = videoUrl

    window.scrollTo({ top: 0, behavior: 'smooth' })

    fetch(`${baseUrl}/api/continuewatch-list`)
      .then((response) => response.json())
      .then(async (data) => {
        const entertainmentId = button.getAttribute('data-entertainment-id')
        const entertainmentType = button.getAttribute('data-entertainment-type')
        const matchingVideo = data.data.find((item) => item.entertainment_id === parseInt(entertainmentId) && item.entertainment_type === entertainmentType)
        let lastWatchedTime = 0
        if (matchingVideo && matchingVideo.total_watched_time) {
          lastWatchedTime = timeStringToSeconds(matchingVideo.total_watched_time)
        }

        if (accessType === 'paid') {
          const canPlay = await checkAuthenticationAndDeviceSupport()
          if (!canPlay) {
            player.pause()
            $('#DeviceSupport').modal('show') // Show device support modal if not supported
            return // Stop further execution
          }
        }

        if (accessType === 'free') {
          playVideo(videoUrl, qualityOptions, lastWatchedTime)
        } else {
          handleSubscription(button, videoUrl, qualityOptions, lastWatchedTime)
        }
      })
      .catch((error) => console.error('Error fetching continue watch:', error))

    isWatchHistorySaved = false // Reset flag
  }

  const watchNowButton = document.getElementById('watchNowButton')
  const seasonWatchBtn = document.getElementById('seasonWatchBtn')

  if (watchNowButton) {
    watchNowButton.addEventListener('click', async function (e) {
      e.preventDefault()
      if (!isAuthenticated) {
        window.location.href = loginUrl // Redirect to login if not authenticated
        return // Stop further execution
      }
      await handleWatchButtonClick(watchNowButton)
    })
  }
  const buttons = document.querySelectorAll('.season-watch-btn');

    buttons.forEach(button => {
      button.addEventListener('click', async function (e) {
        e.preventDefault()
        if (!isAuthenticated) {
          window.location.href = loginUrl // Redirect to login if not authenticated
          return // Stop further execution
        }
        await handleWatchButtonClick(button)
    })
  });

  function playVideo(videoUrl, qualityOptions, lastWatchedTime) {
    const datatype = watchNowButton?.getAttribute('data-type') || seasonWatchBtn?.getAttribute('data-type')

    if(datatype === 'Local') {
      const videoSource = document.querySelectorAll('#videoSource');

    videoSource.src = videoUrl;

    const videoPlayer = videojs('videoPlayer');
    videoPlayer.src({ type: 'video/mp4', src: videoUrl });
    videoPlayer.load();
    videoPlayer.play();
    const existingQualitySelector = document.querySelector('.vjs-quality-selector')
    if (!existingQualitySelector && qualityOptions.length > 0) {
      const qualitySelector = document.createElement('div')
      qualitySelector.classList.add('vjs-quality-selector')

      const qualityDropdown = document.createElement('select')

      qualityOptions.forEach((option) => {
        const qualityOption = document.createElement('option')

        qualityOption.value = option.url.value // Use the URL for the quality option
        qualityOption.innerText = option.label // Display the label (e.g., "360p", "720p")
         qualityOption.setAttribute('data-type', option.url.type);
         qualityDropdown.appendChild(qualityOption)
        })

        qualityDropdown.addEventListener('change', function () {
          const selectedQuality = this.value;
          var videoId = null;
          var platform = null;
          var url = null;

          const dataType = document.querySelector('.vjs-quality-selector select')
                ?.selectedOptions[0]?.getAttribute('data-type');

          const filteredOptions = qualityOptions.filter(option => option.url.type === 'Local' && dataType === option.url.type);
          // Check if a quality option was found and process it
          if (filteredOptions.length > 0) {
            const option = filteredOptions[0]; // Assuming you just want the first match
            const videoSource = document.querySelectorAll('#videoSource'); // Use querySelector for a single element

            if (videoSource) {


                videoSource.src = option.url.value; // Set the local video source

                const videoPlayer = videojs('videoPlayer');
                videoPlayer.src({ type: 'video/mp4', src: option.url.value });
                videoPlayer.load();
                videoPlayer.play();
            }
        } else {
            // Handle external video platforms
            fetch(`${baseUrl}/video/stream/${encodeURIComponent(selectedQuality)}`)
                .then(response => response.json())
                .then(data => {

                    const { videoId, platform } = data;
                    if (platform === 'youtube') {
                        player.src({ type: 'video/youtube', src: `https://www.youtube.com/watch?v=${videoId}` });
                    } else if (platform === 'vimeo') {
                        player.src({ type: 'video/vimeo', src: `https://vimeo.com/${videoId}` });
                    } else if (platform === 'hls') {
                        player.src({ type: 'application/x-mpegURL', src: url });
                    }

                    player.load();
                    player.play();
                })
                .catch(error => console.error('Error playing video:', error));
        }
      });


      qualitySelector.appendChild(qualityDropdown)
      player.controlBar.el().appendChild(qualitySelector)
    }
    } else{

      fetch(`${baseUrl}/video/stream/${encodeURIComponent(videoUrl)}`)
        .then((response) => response.json())
        .then((data) => {

          setVideoSource(player, data.platform, data.videoId, data.url, data.mimeType, qualityOptions)
          player.load()
          player.one('loadedmetadata', async function () {
            const isDeviceSupported = await CheckDeviceType()
            if (isDeviceSupported) {
              player.currentTime(lastWatchedTime)
              if (document.querySelector('#videoPlayer').getAttribute('data-movie-access') === 'free') {
                player.muted(true) // Mute the player for autoplay
                try {
                  await player.play() // Attempt to autoplay
                } catch (error) {
                  console.error('Error trying to autoplay:', error)
                }
              }
            }
          })
        })
        .catch((error) => console.error('Error playing video:', error))
    }
  }

  function handleSubscription(button, videoUrl, qualityOptions, lastWatchedTime) {
    const planId = button.getAttribute('data-plan-id')
    fetch(`${baseUrl}/check-subscription/${planId}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.isActive) {
          playVideo(videoUrl, qualityOptions, lastWatchedTime)
        } else {
          // Open the modal to show the user options for selecting or confirming a plan
          $('#DeviceSupport').modal('show')

          // Assuming you have a button inside the modal to proceed with payment
          document.querySelector('#confirmSubscriptionButton').addEventListener('click', function () {
            // Redirect to subscription plan after modal confirmation
            window.location.href = `${baseUrl}/subscription-plan`
          })
        }
      })
      .catch((error) => console.error('Error checking subscription:', error))
  }

  player.on('ended', async function () {
    if (isWatchHistorySaved) return

    const entertainmentId = watchNowButton?.getAttribute('data-entertainment-id') || seasonWatchBtn?.getAttribute('data-entertainment-id')
    const entertainmentType = watchNowButton?.getAttribute('data-entertainment-type') || seasonWatchBtn?.getAttribute('data-entertainment-type')
    const profileId = watchNowButton?.getAttribute('data-profile-id') || seasonWatchBtn?.getAttribute('data-profile-id')

    if (isAuthenticated && entertainmentId && entertainmentType && profileId) {
      const isDeviceSupported = await CheckDeviceType()
      if (!isDeviceSupported) {
        $('#DeviceSupport').modal('show')
        return
      }

      const watchHistoryData = {
        entertainment_id: entertainmentId,
        entertainment_type: entertainmentType,
        profile_id: profileId
      }

      fetch(`${baseUrl}/api/save-watch-content`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(watchHistoryData)
      })
        .then((response) => response.json())
        .then((data) => {
          isWatchHistorySaved = true
        })
        .catch((error) => console.error('Error saving watch history:', error))
    }
  })

  window.addEventListener('beforeunload', async function () {
    const entertainmentId = watchNowButton?.getAttribute('data-entertainment-id') || seasonWatchBtn?.getAttribute('data-entertainment-id')
    const entertainmentType = watchNowButton?.getAttribute('data-entertainment-type') || seasonWatchBtn?.getAttribute('data-entertainment-type')
    const EpisodeId = watchNowButton?.getAttribute('data-episode-id') || seasonWatchBtn?.getAttribute('data-episode-id')




    if (isAuthenticated && currentVideoUrl && entertainmentId && entertainmentType) {
      const currentTime = player.currentTime()
      const totalWatchedTime = new Date(currentTime * 1000).toISOString().substr(11, 8)

      fetch(`${baseUrl}/api/save-continuewatch`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          entertainment_id: entertainmentId,
          entertainment_type: entertainmentType,
          total_watched_time: totalWatchedTime,
          watched_time:totalWatchedTime,
          episode_id:EpisodeId,
          video_url: currentVideoUrl
        })
      })
        .then((response) => response.json())
        .then((data) => {

        })
        .catch((error) => console.error('Error saving continue watching:', error))
    }
  })

  function setVideoSource(player, platform, videoId, url = '', mimeType = '', qualityOptions = []) {


    if (platform === 'youtube') {
      player.src({ type: 'video/youtube', src: `https://www.youtube.com/watch?v=${videoId}` })
    } else if (platform === 'vimeo') {
      player.src({ type: 'video/vimeo', src: `https://vimeo.com/${videoId}` })
    } else if (platform === 'hls') {
      player.src({ type: 'application/x-mpegURL', src: url })
    } else if (platform === 'local') {

      player.src({ type: mimeType, src: url })
    }

    const existingQualitySelector = document.querySelector('.vjs-quality-selector')
    if (!existingQualitySelector && qualityOptions.length > 0) {
      const qualitySelector = document.createElement('div')
      qualitySelector.classList.add('vjs-quality-selector')

      const qualityDropdown = document.createElement('select')

      qualityOptions.forEach((option) => {
        const qualityOption = document.createElement('option')



        qualityOption.value = option.url.value // Use the URL for the quality option
        qualityOption.innerText = option.label // Display the label (e.g., "360p", "720p")
         qualityOption.setAttribute('data-type', option.url.type);
         qualityDropdown.appendChild(qualityOption)
        })

      qualityDropdown.addEventListener('change', function () {
        const selectedQuality = this.value



        var videoId = null
        var platform = null
        var url = null
          qualityOptions.forEach((option) => {
            if(option.url.type === 'Local'){


              const videoSource = document.querySelectorAll('#videoSource');
              videoSource.src = option.url.value;

              const videoPlayer = videojs('videoPlayer');
              videoPlayer.src({ type: 'video/mp4', src: option.url.value });
              videoPlayer.load();
              videoPlayer.play();
              }else{
                fetch(`${baseUrl}/video/stream/${encodeURIComponent(selectedQuality)}`)
                        .then((response) => response.json())
                        .then((data) => {

                          videoId = data.videoId
                          platform = data.platform

                          if (platform == 'youtube') {
                            player.src({ type: 'video/youtube', src: `https://www.youtube.com/watch?v=${videoId}` })
                          } else if (platform === 'vimeo') {
                            player.src({ type: 'video/vimeo', src: `https://vimeo.com/${videoId}` })
                          } else if (platform === 'hls') {
                            player.src({ type: 'application/x-mpegURL', src: url })
                          }
                        })
                        .catch((error) => console.error('Error playing video:', error))
                player.load()
                player.play() // Play the selected quality
              }
          })
      })

      qualitySelector.appendChild(qualityDropdown)
      player.controlBar.el().appendChild(qualitySelector)
    }
  }

  function timeStringToSeconds(timeString) {
    const [hours, minutes, seconds] = timeString.split(':').map(Number)
    return hours * 3600 + minutes * 60 + seconds
  }
})
