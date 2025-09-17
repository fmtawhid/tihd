<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<style>
/* Bottom Fixed Menu for Mobile */
.bottom-menu {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background: rgba(0, 0, 0, 0.6); /* Semi-transparent black */
  backdrop-filter: blur(8px);      /* Blur effect */
  -webkit-backdrop-filter: blur(8px);
  display: flex;
  justify-content: space-around;
  padding: 6px 0;
  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
  z-index: 1000;
  display: none;  /* Initially hidden */
  border-radius: 15px 15px 0 0;
}

.bottom-menu a {
  color: white;
  font-size: 13px;
  text-decoration: none;
  text-align: center;
  padding: 4px;
  transition: all 0.3s ease;
}

@media (max-width: 1200px) {
  .bottom-menu {
    display: flex;
  }
}

.bottom-menu .icon {
  font-size: 16px; /* Small default icon size */
  transition: transform 0.3s ease, font-size 0.3s ease;
}

/* Active icon effect */
.bottom-menu a.active .icon {
  font-size: 20px;
  color: #e50914;
}

.bottom-menu a.active {
  font-weight: 700;
}

/* Label text */
.bottom-menu a p {
  font-size: 11px;
  opacity: 0.8;
  margin-bottom: 0;
  transition: opacity 0.3s ease;
}
</style>

<!-- Bottom Fixed Menu -->
<div class="bottom-menu d-md-none d-xl-none">
  <a href="{{ route('user.login') }}" title="Home" class="{{ request()->routeIs('user.login') ? 'active' : '' }}">
    <span class="icon fas fa-house-user"></span>
    <p>{{__('frontend.home')}}</p>
  </a>

  @if(isenablemodule('movie'))
    <a href="{{ route('movies') }}" title="Movies" class="{{ request()->routeIs('movies') ? 'active' : '' }}">
      <span class="icon fas fa-video"></span>
      <p>{{__('frontend.movies')}}</p>
    </a>
  @endif

  @if(isenablemodule('tvshow'))
    <a href="{{ route('tv-shows') }}" title="Series" class="{{ request()->routeIs('tv-shows') ? 'active' : '' }}">
      <span class="icon fas fa-tv"></span>
      <p>{{__('frontend.tvshows')}}</p>
    </a>
  @endif


  @if(isenablemodule('livetv'))
    <a href="{{ route('livetv') }}" title="Live TV" class="{{ request()->routeIs('livetv') ? 'active' : '' }}">
      <span class="icon fas fa-satellite-dish"></span>
      <p>{{__('frontend.livetv')}}</p>
    </a>
  @endif

  @if(auth()->user())
    <a href="{{ route('edit-profile') }}" title="Profile" class="{{ request()->routeIs('comingsoon') ? 'active' : '' }}">
      <span class="icon fas fa-user-circle"></span>
      <p>{{__('frontend.profile')}}</p>
    </a>
  @endif
</div>
