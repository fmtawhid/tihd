
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <style>



/* Bottom Fixed Menu for Mobile */
.bottom-menu {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background-color: #E50914;
  display: flex;
  justify-content: space-around;
  padding: 1px 0;
  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
  z-index: 1000;
  display: none;  /* Initially hidden */
  border-radius: 30px 30px 0 0;
}

.bottom-menu a {
  color: white;
  font-size: 15px;
  text-decoration: none;
  text-align: center;
  padding: 5px;
  transition: all 0.3s ease;
}


/* Show the menu only for mobile (screen width <= 768px) */
@media (max-width: 1200px) {
  .bottom-menu {
    display: flex;
  }
}

.bottom-menu .icon {
  font-size: 15px; /* Default icon size */
  transition: transform 0.3s ease, font-size 0.3s ease; /* Transition for icon size */
}

/* When an icon is active, increase its font size */
.bottom-menu a.active .icon {
  font-size: 25px; /* Increased size when active */
}

.bottom-menu a.active  {
  font-weight: 800;
}



.bottom-menu a p {
  font-size: 12px;
  opacity: 0.8;
  transition: opacity 0.3s ease;
  margin-bottom: 0px;
}



/* Active state for the text of the active item */


  </style>

<!-- Bottom Fixed Menu for Mobile -->
<div class="bottom-menu d-md-none d-xl-none">
  <a href="{{ route('user.login') }}" title="Home" class="{{ request()->routeIs('user.login') ? 'active' : '' }}">
    <span class="icon fas fa-home"></span> <!-- Home Icon -->
    <p>{{__('frontend.home')}}</p>
  </a>

  @if(isenablemodule('movie'))
    <a href="{{ route('movies') }}" title="Movies" class="{{ request()->routeIs('movies') ? 'active' : '' }}">
      <span class="icon fas fa-tv"></span> <!-- Movies Icon -->
      <p>{{__('frontend.movies')}}</p>
    </a>
  @endif

  @if(isenablemodule('tvshow'))
    <a href="{{ route('tv-shows') }}" title="Series" class="{{ request()->routeIs('tv-shows') ? 'active' : '' }}">
      <span class="icon fas fa-film"></span> <!-- TV Shows Icon -->
      <p>{{__('frontend.tvshows')}}</p>
    </a>
  @endif

  @if(isenablemodule('video'))
    <a href="{{ route('videos') }}" title="Videos" class="{{ request()->routeIs('videos') ? 'active' : '' }}">
      <span class="icon fas fa-video"></span> <!-- Video Icon -->
      <p>{{__('frontend.video')}}</p>
    </a>
  @endif

  @if(isenablemodule('livetv'))
    <a href="{{ route('livetv') }}" title="Live TV" class="{{ request()->routeIs('livetv') ? 'active' : '' }}">
      <span class="icon fas fa-broadcast-tower"></span> <!-- Live TV Icon -->
      <p>{{__('frontend.livetv')}}</p>
    </a>
  @endif

  @if(auth()->user())
    <a href="{{ route('comingsoon') }}" title="Watch History" class="{{ request()->routeIs('comingsoon') ? 'active' : '' }}">
      <span class="icon fas fa-history"></span> <!-- Watch History Icon -->
      <p>{{__('frontend.watch_history')}}</p>
    </a>
  @endif
</div>




