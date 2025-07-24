<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark" dir="{{ session()->has('dir') ? session()->get('dir') : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="baseUrl" content="{{url('/')}}" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset(setting('favicon')) }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    @yield('meta')
  
  	<meta name="theme-color" content="#ffffff">
	<link rel="manifest" href="{{ asset('manifest.json') }}">




    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('modules/frontend/style.css') }}">
    <link rel="stylesheet" href="{{ asset('modules/frontend/tv.css') }}">

    <link rel="stylesheet" href="{{ asset('iconly/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('phosphor-icons/regular/style.css') }}">
    <link rel="stylesheet" href="{{ asset('phosphor-icons/fill/style.css') }}">
    <link rel="shortcut icon" href="{{ asset(setting('favicon')) }}">
    <link rel="icon" type="image/ico" href="{{ asset(setting('favicon')) }}" />
    @include('frontend::components.partials.head.plugins')
    @stack('after-styles')
    {{-- Vite CSS --}}
    {{-- {{ module_vite('build-frontend', 'resources/assets/sass/app.scss') }} --}}
</head>

<body class="{{ Route::currentRouteName() == 'search' ? 'search-page' : '' }}">
    @include('frontend::layouts.header')

    @yield('content')

    @include('frontend::layouts.footer')
    @include('frontend::layouts.mobile_menu')

    @include('frontend::components.partials.back-to-top')

    @include('frontend::components.partials.scripts.plugins')


    <script src="{{ mix('modules/frontend/script.js') }}"></script>
    <script src="{{ mix('js/backend-custom.js') }}"></script>
  
  
  <script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').then(function(registration) {
            console.log('Service Worker registered with scope:', registration.scope);
        }).catch(function(error) {
            console.log('Service Worker registration failed:', error);
        });
    }
</script>
  
  
    {{-- Vite JS --}}
    {{-- {{ module_vite('build-frontend', 'resources/assets/js/app.js') }} --}}
    @stack('after-scripts')
    <script>

    const currencyFormat = (amount) => {
        const DEFAULT_CURRENCY = JSON.parse(@json(json_encode(Currency::getDefaultCurrency(true))))
         const noOfDecimal = DEFAULT_CURRENCY.no_of_decimal
         const decimalSeparator = DEFAULT_CURRENCY.decimal_separator
         const thousandSeparator = DEFAULT_CURRENCY.thousand_separator
         const currencyPosition = DEFAULT_CURRENCY.currency_position
         const currencySymbol = DEFAULT_CURRENCY.currency_symbol
        return formatCurrency(amount, noOfDecimal, decimalSeparator, thousandSeparator, currencyPosition, currencySymbol)
      }

      window.currencyFormat = currencyFormat
      window.defaultCurrencySymbol = @json(Currency::defaultSymbol())

    </script>
</body>
