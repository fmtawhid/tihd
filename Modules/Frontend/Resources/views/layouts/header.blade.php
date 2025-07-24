<header class="header-center-home header-default header-sticky header-one {{ Route::currentRouteName() == 'user.login' ? 'header-absolute' : '' }}">
    <nav class="nav navbar navbar-expand-xl navbar-light iq-navbar header-hover-menu py-xl-0">
        <div class="container-fluid navbar-inner">
            <div class="d-flex align-items-center justify-content-between w-100 landing-header">
                <div class="d-flex gap-3 gap-xl-0 align-items-center">

                    
                    

                    <button type="button" data-bs-toggle="offcanvas" data-bs-target="#navbar_main"
                        aria-controls="navbar_main"
                        class="d-none d-md-block d-xl-none d-sm-none btn btn-primary rounded-pill toggle-rounded-btn atv">
                        <i class="ph ph-arrow-right"></i>
                    </button>

                    <!--Logo -->
                    @include('frontend::components.partials.logo')
                </div>

                <!-- navigation -->
                @include('frontend::components.partials.horizontal-nav')

                <div class="right-panel ">

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-btn">
                            <span class="navbar-toggler-icon"></span>
                        </span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <div class="d-flex flex-md-row flex-column align-items-md-center align-items-end justify-content-end gap-xl-4 gap-0">
                            <ul class="navbar-nav align-items-center list-inline justify-content-end mt-md-0 mt-3">
                                <li class="flex-grow-1">
                                    <div class="search-box position-relative text-end">
                                        <a href="#" class="nav-link p-0 d-md-inline-block d-none" id="search-drop" data-bs-toggle="dropdown">
                                           <div class="btn-icon btn-sm rounded-pill btn-action">
                                              <span class="btn-inner">
                                                 <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    </circle>
                                                    <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    </path>
                                                 </svg>
                                              </span>
                                           </div>
                                        </a>
                                        <ul class="dropdown-menu p-0 dropdown-search m-0 iq-search-bar" style="width: 20rem;">
                                           <li class="p-0">
                                              <div class="form-group input-group mb-0">
                                                <button type="submit" id="search-button" class="search-submit">
                                                    <svg class="icon-15" width="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                        </circle>
                                                        <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                        </path>
                                                    </svg>
                                                 </button>
                                                 <button type="submit" class="remove-search d-none">
                                                    <i class="ph ph-x"></i>
                                                 </button>
                                                </button>
                                                <input type="text" id="search-query" class="form-control border-0" placeholder="Search...">
                                              </div>
                                           </li>
                                        </ul>
                                     </div>
                                </li>
                            </ul>
                            <ul class="navbar-nav align-items-center mb-0 ps-0 justify-content-end">
                                {{-- <li>
                                    <a href="{{ route('search') }}" class="btn btn-dark px-3 d-flex gap-1">
                                         <i class="ph ph-magnifying-glass align-middle"></i>
                                         <span class="ms-1 d-none d-sm-block">Search</span>
                                    </a>
                                </li> --}}
                                <li class="nav-item dropdown dropdown-language-wrapper">
                                    <button class="btn btn-dark gap-3 px-3 dropdown-toggle atv" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <img src="{{ asset('flags/' . App::getLocale() . '.png') }}" alt="flag" class="img-fluid me-2" style="width: 20px; height: auto; min-width: 15px;"
                                        onerror="this.onerror=null; this.src='{{asset('flags/globe.png')}}';">
                                        {{ strtoupper(App::getLocale()) }}
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-sm-end dropdown-menu-language mt-0 atv responsivelangmb">
                                        @foreach (config('app.available_locales') as $locale => $title)
                                            <a class="dropdown-item" href="{{ route('frontend.language.switch', $locale) }}">
                                                <span class="d-flex align-items-center gap-3">
                                                    <img src="{{ asset('flags/' . $locale . '.png') }}" alt="flag" class="img-fluid mr-2"style="width: 20px;height: auto;min-width: 15px;">
                                                    <span> {{ $title }}</span>
                                                    <span class="active-icon"><i class="ph-fill ph-check-fat align-middle"></i></span>
                                                </span>
                                            </a>
                                        @endforeach
                                    </div>
                                </li>

                                @if(auth()->check())
                                    @if(auth()->user()->user_type == 'user')
                                        <li class="nav-item">

                                            @if(auth()->user()->is_subscribe==0)
                                            <button class="btn btn-warning-subtle font-size-14 text-uppercase subscribe-btn" onclick="window.location.href='{{ route('subscriptionPlan') }}'">
                                                {{__('frontend.subscribe')}}
                                            </button>
                                            @else

                                            <button class="btn btn-warning-subtle font-size-14 text-uppercase subscribe-btn" onclick="window.location.href='{{ route('subscriptionPlan') }}'">
                                                {{__('frontend.upgrade')}}
                                            </button>

                                            @endif

                                        </li>
                                    @endif
                                    <li class="nav-item flex-shrink-0 dropdown dropdown-user-wrapper">
                                        <a class="nav-link dropdown-user" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <img src="{{ setBaseUrlWithFileName(auth()->user()->file_url)}}" class="img-fluid user-image rounded-circle" alt="user image">
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-user-menu border border-gray-900" aria-labelledby="navbarDropdown">
                                            <div class="bg-body p-3 d-flex justify-content-between align-items-center gap-3 rounded selectfortv">
                                                <div class="d-inline-flex align-items-center gap-3">
                                                    <div class="image flex-shrink-0">
                                                        <img src="{{ setBaseUrlWithFileName(auth()->user()->file_url) }}" class="img-fluid dropdown-user-menu-image" alt="">
                                                    </div>
                                                    <div class="content">
                                                        <h6 class="mb-1"> {{ auth()->user()->full_name ?? default_user_name() }}</h6>
                                                        <span class="font-size-14 dropdown-user-menu-contnet"> {{ auth()->user()->email}}</span>
                                                    </div>
                                                </div>
                                                <div class="link">
                                                    <a href="{{ route('edit-profile') }}" class="link-body-emphasis">
                                                        <i class="ph ph-caret-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <ul class="d-flex flex-column gap-3 list-inline m-0 p-0 hidefortv">
                                                <li class="selectfortv" tabindex="0" style="padding: 8px 2px;">
                                                    <a href="{{ route('watchList') }}" class="link-body-emphasis font-size-14">
                                                        <span class="d-flex align-items-center justify-content-between gap-3 ">
                                                            <span class="fw-medium">{{__('frontend.my_watchlist')}}</span>
                                                            <i class="ph ph-caret-right "></i>
                                                        </span>
                                                    </a>
                                                </li>

                                                <li class="selectfortv" tabindex="1" style="padding: 8px 2px;">
                                                    <a href="{{ route('edit-profile') }}" class="link-body-emphasis font-size-14">
                                                        <span class="d-flex align-items-center justify-content-between gap-3">
                                                            <span class="fw-medium">{{__('frontend.profile')}}</span>
                                                            <i class="ph ph-caret-right "></i>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="selectfortv" tabindex="2" style="padding: 8px 2px;">
                                                    <a href="{{ route('subscriptionPlan') }}" class="link-body-emphasis font-size-14">
                                                        <span class="d-flex align-items-center justify-content-between gap-3">
                                                            <span class="fw-medium">{{__('frontend.subscription_plan')}}</span>
                                                            <i class="ph ph-caret-right "></i>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="selectfortv" tabindex="3" style="padding: 8px 2px;">
                                                    <a href="{{ route('accountSetting') }}" class="link-body-emphasis font-size-14">
                                                        <span class="d-flex align-items-center justify-content-between gap-3">
                                                            <span class="fw-medium">{{__('frontend.account_setting')}}</span>
                                                            <i class="ph ph-caret-right "></i>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="selectfortv" tabindex="4" style="padding: 8px 2px;">
                                                    <a href="{{ route('payment-history') }}" class="link-body-emphasis font-size-14">
                                                        <span class="d-flex align-items-center justify-content-between gap-3">
                                                            <span class="fw-medium">{{__('frontend.subscription_history')}}</span>
                                                            <i class="ph ph-caret-right "></i>
                                                        </span>
                                                    </a>
                                                </li>

                                                <li class="selectfortv" tabindex="5" style="padding: 8px 2px;">
                                                    <a href="{{ route('user-logout') }}" class="link-primary font-size-14">
                                                        <span class="d-flex align-items-center justify-content-between gap-3">
                                                            <span class="fw-medium">{{__('frontend.logout')}}</span>
                                                        </span>
                                                    </a>
                                                </li>
                                            </ul>

                                        </div>
                                    </li>
                                    
                                @else
                                    <li class="nav-item" style="padding: 8px 2px;">
                                        <a href="{{ route('login') }}" class="btn btn-primary font-size-14 login-btn atv">
                                            {{__('frontend.login')}}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
<script>
    window.onload = function() {
    const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    const urlParams = new URLSearchParams(window.location.search);
    const query = urlParams.get('query');
    document.getElementById('search-query').value = query;
    const envURL = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
    const searchButton = document.getElementById('search-button');
    const searchInput = document.getElementById('search-query');


    // Handle search button click
    searchButton.addEventListener('click', function(e) {
        e.preventDefault();
        const query = searchInput.value.trim();

        if (query) {
            // Redirect to the search page with query as a parameter
            window.location.href = `${envURL}/search?query=${encodeURIComponent(query)}`;
        }
    });

    // Add event listener for Enter key press
    searchInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            searchButton.click();
        }
    });
};

</script>
