<div class="detail-page-banner">
  <div class="video-player">

    @if($type=='Local')

    <video id="videoPlayer" class="video-js vjs-default-skin" controls width="560" height="315"
      autoplay="{{ auth()->check() ? 'true' : 'false' }}" muted data-setup="{}" poster="{{$thumbnail_image}}"
      data-setup='{"autoplay": {{ auth()->check() ? ' true' : 'false' }}, "muted" : true}'>
      <source src="{{ $data }}" type="video/mp4" id="videoSource">

    </video>
    <div class="d-flex justify-content-end gap-3 mt-2 px-5">
      <button id="button" class="action-btn btn-dark d-flex gap-2 items-center"><img height="28" src='https://tihd.tv/img/skip2.svg' /></button>
      <button id="button2" class="action-btn btn btn-dark d-flex gap-2 items-center"><img height="28" src='https://tihd.tv/img/skip2.svg' /></button>
    </div>


    @else

    <!-- Video.js Player -->
    <!-- <video id="videoPlayer" class="video-js vjs-default-skin" controls width="560" height="315"
      autoplay="{{ auth()->check() ? 'true' : 'false' }}" muted caption skio data-movie-access="{{$dataAccess??''}}"
      data-encrypted="{{ $data }}" poster="{{$thumbnail_image}}"
      data-setup='{"playbackRates": [1, 1.5, 2], "muted": true}'>
    </video> -->
    <video id="videoPlayer"
       class="video-js vjs-default-skin"
       controls
       playsinline
       webkit-playsinline
       x5-playsinline
       width="560"
       height="315"
       autoplay="{{ auth()->check() ? 'true' : 'false' }}"
       muted
       poster="{{ $thumbnail_image }}"
       data-movie-access="{{ $dataAccess ?? '' }}"
       data-encrypted="{{ $data }}"
       data-setup='{"playbackRates": [1, 1.5, 2], "muted": true}'>
    </video>


    <div class="d-flex justify-content-end gap-3 mt-2 px-5">
      <button id="button" class="action-btn btn btn-dark d-flex gap-2 btn-sm items-center"><img height="28" src='https://tihd.tv/img/skip2.svg' /></button>
      <button id="button2" class="action-btn btn btn-dark d-flex btn-sm gap-2 items-center"><img height="28" src='https://tihd.tv/img/skip2.svg' /></button>
    </div>
    @endif

  </div>
</div>


<script>
  var video = document.getElementById("videoPlayer");
  var button = document.getElementById("button");
  var button2 = document.getElementById("button2");

  button.addEventListener("click", function (e) {
    video.play();
    video.pause();
    video.currentTime = video.currentTime - 10;
    video.play();
  })

  button2.addEventListener("click", function (e) {
    video.play();
    video.pause();
    video.currentTime = video.currentTime + 10;
    video.play();
  })


  document.addEventListener('keydown', (event) => {
    if (event.key === 'ArrowLeft') {
      video.currentTime = video.currentTime - 10;
    } else if (event.key === 'ArrowRight') {
      video.currentTime = video.currentTime + 10;
    }
  });

</script>



<!-- Include the custom JS -->
<script src="{{ asset('js/videoplayer.min.js') }}"></script>
<script>
  var isAuthenticated = {{ auth() -> check() ? 'true' : 'false' }};
  var loginUrl = "{{ route('login') }}"; 
</script>