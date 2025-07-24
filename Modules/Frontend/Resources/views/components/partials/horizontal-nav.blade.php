<!-- Horizontal Menu Start -->
<nav id="navbar_main" class="offcanvas mobile-offcanvas nav navbar navbar-expand-xl hover-nav horizontal-nav py-xl-0">
  <div class="container-fluid p-lg-0">
    <div class="offcanvas-header">
      <div class="navbar-brand p-0">
        <!--Logo -->
        @include('frontend::components.partials.logo')
      </div>
      <a href="/" type="button" data-bs-dismiss="offcanvas" aria-label="Close">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="4" x2="6" y2="18"></line>
              <line x1="6" y1="4" x2="18" y2="18"></line>
          </svg>
      </a>
      <!-- <button type="button" class="btn-close p-0 selectfortv" data-bs-dismiss="offcanvas" aria-label="Close"></button> -->
    </div>
    <ul class="navbar-nav iq-nav-menu  list-unstyled" id="header-menu">
      
      
      <h5 class="showfortv pb-2">Main Menu</h5>
      <li class="nav-item atv">
        <a class="nav-link"  href="{{route('user.login')}}">
          <span class="item-name">{{__('frontend.home')}}</span>
        </a>
      </li>
      @if(isenablemodule('movie'))
      <li class="nav-item atv">
        <a class="nav-link"  href="{{ route('movies') }}">
          <span class="item-name">{{__('frontend.movies')}}</span>
        </a>
      </li>
      @endif
      @if(isenablemodule('tvshow'))
      <li class="nav-item atv">
        <a class="nav-link"  href="{{ route('tv-shows') }}">
          <span class="item-name">{{__('frontend.tvshows')}}</span>
        </a>
      </li>
      @endif
      @if(isenablemodule('video'))
      <li class="nav-item atv">
        <a class="nav-link"  href="{{ route('videos') }}">
          <span class="item-name">{{__('frontend.video')}}</span>
        </a>
      </li>
      @endif
      
      @if(isenablemodule('livetv'))
      <li class="nav-item atv">
        <a class="nav-link"  href="{{route('livetv')}}">
          <span class="item-name">{{__('frontend.livetv')}}</span>
        </a>
      </li>
      @endif
      @if(auth()->user())
      <h5 class="showfortv pb-2">Account Menu</h5>
      
      
      <li class="nav-item showfortv">
        <a class="nav-link"  href="{{ route('comingsoon') }}">
          <span class="item-name">{{__('frontend.watch_history')}}</span>
        </a>
      </li>

      <li class="nav-item atv showfortv">
        <a class="nav-link"  href="{{ route('watchList') }}">
          <span class="item-name">{{__('frontend.my_watchlist')}}</span>
        </a>
      </li>

      <li class="nav-item atv showfortv">
        <a class="nav-link"  href="{{ route('edit-profile') }}">
          <span class="item-name">{{__('frontend.profile')}}</span>
        </a>
      </li>

      <li class="nav-item atv showfortv">
        <a class="nav-link"  href="{{ route('subscriptionPlan') }}">
          <span class="item-name">{{__('frontend.subscription_plan')}}</span>
        </a>
      </li>


      <li class="nav-item atv showfortv">
        <a class="nav-link"  href="{{ route('accountSetting') }}">
          <span class="item-name">{{__('frontend.account_setting')}}</span>
        </a>
      </li>


      <li class="nav-item atv showfortv">
        <a class="nav-link"  href="{{ route('payment-history') }}">
          <span class="item-name">{{__('frontend.subscription_history')}}</span>
        </a>
      </li>


      <li class="nav-item atv showfortv">
        <a class="nav-link"  href="{{ route('user-logout') }}">
          <span class="item-name">{{__('frontend.logout')}}</span>
        </a>
      </li>


      @endif
      
      
      
    </ul>
  </div>
  <!-- container-fluid.// -->
</nav>
<!-- Horizontal Menu End -->
