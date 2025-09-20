<div class="detail-page-banner">
  <div class="video-player position-relative">

    @if($type=='Local')
    <video id="videoPlayer" class="video-js vjs-default-skin"
      controls width="560" height="315"
      autoplay="{{ auth()->check() ? 'true' : 'false' }}" muted
      poster="{{ $thumbnail_image }}"
      data-setup='{"autoplay": {{ auth()->check() ? "true" : "false" }}, "muted" : false}'>
      <source src="{{ $data }}" type="video/mp4" id="videoSource">
    </video>
    @else
    <!-- Video.js Player -->
    <video id="videoPlayer"
       class="video-js vjs-default-skin"
       controls
       playsinline webkit-playsinline x5-playsinline
       width="560" height="315"
       autoplay="{{ auth()->check() ? 'true' : 'false' }}"
       muted
       poster="{{ $thumbnail_image }}"
       data-movie-access="{{ $dataAccess ?? '' }}"
       data-encrypted="{{ $data }}"
       data-setup='{"playbackRates": [1, 1.5, 2], "muted": true}'>
    </video>
    @endif

    <!-- Overlay Zones -->
    <div id="leftZone" class="zone left"></div>
    <div id="centerZone" class="zone center"></div>
    <div id="rightZone" class="zone right"></div>

    <!-- Skip Animation Icons -->
    <div id="skipBack" class="skip-icon"> << </div>
    <div id="skipForward" class="skip-icon"> >></div>
  </div>
</div>

<style>
  .video-player {
    position: relative;
    /* display: inline-block; */
  }
  .zone {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 33.33%;
    cursor: pointer;
    z-index: 5;
  }
  .zone.left   { left: 0; }
  .zone.center { left: 33.33%; }
  .zone.right  { right: 0; }

  /* Skip Animation Icons */
  .skip-icon {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 2rem;
    color: white;
    /* background: rgba(0,0,0,0.6); */
    padding: 10px 20px;
    border-radius: 10px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.5s ease;
    z-index: 10;
  }
  #skipBack { left: 10%; }
  #skipForward { right: 10%; }
  .skip-icon.show {
    opacity: 1;
  }
  .vjs-audio-only-mode .vjs-control-bar, .vjs-has-started .vjs-control-bar {
  z-index: 999;
  }
</style>

<script>
  var video = document.getElementById("videoPlayer");
  var button = document.getElementById("button");
  var button2 = document.getElementById("button2");

  // আগের skip বাটন
  if (button) {
    button.addEventListener("click", function () {
      video.currentTime = Math.max(video.currentTime - 10, 0);
    });
  }
  if (button2) {
    button2.addEventListener("click", function () {
      video.currentTime = Math.min(video.currentTime + 10, video.duration);
    });
  }

  // Overlay zones
  const leftZone = document.getElementById("leftZone");
  const rightZone = document.getElementById("rightZone");
  const centerZone = document.getElementById("centerZone");

  // Skip animation elements
  const skipBack = document.getElementById("skipBack");
  const skipForward = document.getElementById("skipForward");

  function togglePlay() {
    if (video.paused) {
      video.play();
    } else {
      video.pause();
    }
  }

  function showAnimation(el) {
    el.classList.add("show");
    setTimeout(() => el.classList.remove("show"), 800);
  }

  // Desktop clicks
  leftZone.addEventListener("dblclick", () => {
    video.currentTime = Math.max(video.currentTime - 10, 0);
    showAnimation(skipBack);
  });
  rightZone.addEventListener("dblclick", () => {
    video.currentTime = Math.min(video.currentTime + 10, video.duration);
    showAnimation(skipForward);
  });
  centerZone.addEventListener("click", togglePlay);

  // Touch support
  function handleTap(zone, singleTap, doubleTap) {
    let lastTap = 0;
    zone.addEventListener("touchend", function (e) {
      const currentTime = new Date().getTime();
      const tapLength = currentTime - lastTap;
      if (tapLength < 300 && tapLength > 0) {
        doubleTap();
      } else {
        setTimeout(() => {
          if (new Date().getTime() - currentTime >= 300) {
            singleTap();
          }
        }, 300);
      }
      lastTap = currentTime;
      e.preventDefault();
    });
  }

  // Apply touch handling
  handleTap(centerZone, togglePlay, togglePlay);
  handleTap(leftZone, ()=>{}, ()=>{
    video.currentTime = Math.max(video.currentTime - 10, 0);
    showAnimation(skipBack);
  });
  handleTap(rightZone, ()=>{}, ()=>{
    video.currentTime = Math.min(video.currentTime + 10, video.duration);
    showAnimation(skipForward);
  });

  // Keyboard shortcuts
  document.addEventListener('keydown', (event) => {
    if (!video) return;

    switch (event.code) {
      case 'ArrowLeft':
        video.currentTime = Math.max(video.currentTime - 10, 0);
        showAnimation(skipBack);
        event.preventDefault();
        break;
      case 'ArrowRight':
        video.currentTime = Math.min(video.currentTime + 10, video.duration);
        showAnimation(skipForward);
        event.preventDefault();
        break;
      case 'Space':
        togglePlay();
        event.preventDefault();
        break;
      case 'ArrowUp':
        video.volume = Math.min(video.volume + 0.1, 1);
        event.preventDefault();
        break;
      case 'ArrowDown':
        video.volume = Math.max(video.volume - 0.1, 0);
        event.preventDefault();
        break;
    }
  });
</script>
<script>
var player = videojs('videoPlayer', {
    inactivityTimeout: 0 // Disable mouse hide
});

function setupOverlayZones(container) {
    const leftZone = container.querySelector('.zone.left');
    const rightZone = container.querySelector('.zone.right');
    const centerZone = container.querySelector('.zone.center');

    const skipBack = container.querySelector('#skipBack');
    const skipForward = container.querySelector('#skipForward');

    function togglePlay() {
        if (player.paused()) player.play();
        else player.pause();
    }
    function showAnimation(el) {
        el.classList.add("show");
        setTimeout(() => el.classList.remove("show"), 800);
    }

    // Remove previous listeners
    leftZone.replaceWith(leftZone.cloneNode(true));
    rightZone.replaceWith(rightZone.cloneNode(true));
    centerZone.replaceWith(centerZone.cloneNode(true));

    const newLeft = container.querySelector('.zone.left');
    const newRight = container.querySelector('.zone.right');
    const newCenter = container.querySelector('.zone.center');

    newLeft.addEventListener("dblclick", () => {
        player.currentTime(Math.max(player.currentTime() - 10, 0));
        showAnimation(skipBack);
    });
    newRight.addEventListener("dblclick", () => {
        player.currentTime(Math.min(player.currentTime() + 10, player.duration()));
        showAnimation(skipForward);
    });
    newCenter.addEventListener("click", togglePlay);

    function handleTap(zone, singleTap, doubleTap) {
        let lastTap = 0;
        zone.addEventListener("touchend", function (e) {
            const currentTime = new Date().getTime();
            const tapLength = currentTime - lastTap;
            if (tapLength < 300 && tapLength > 0) doubleTap();
            else setTimeout(() => { if (new Date().getTime() - currentTime >= 300) singleTap(); }, 300);
            lastTap = currentTime;
            e.preventDefault();
        });
    }

    handleTap(newCenter, togglePlay, togglePlay);
    handleTap(newLeft, ()=>{}, ()=> {
        player.currentTime(Math.max(player.currentTime() - 10, 0));
        showAnimation(skipBack);
    });
    handleTap(newRight, ()=>{}, ()=> {
        player.currentTime(Math.min(player.currentTime() + 10, player.duration()));
        showAnimation(skipForward);
    });
}

// Initial setup
setupOverlayZones(document.querySelector('.video-player'));

// Fullscreen rotation + overlay setup
player.on('fullscreenchange', function() {
    const container = player.el();
    if (player.isFullscreen()) {
        container.style.transform = 'rotate(90deg)';
        container.style.transition = 'transform 0.5s ease';
    } else {
        container.style.transform = 'rotate(0deg)';
    }

    setupOverlayZones(container);
});

</script>

<!-- Include the custom JS -->
<script src="{{ asset('js/videoplayer.min.js') }}"></script>
<script>
  var isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
  var loginUrl = "{{ route('login') }}"; 
</script>
