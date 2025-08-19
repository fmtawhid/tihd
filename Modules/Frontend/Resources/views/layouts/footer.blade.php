@php
$footerData = getFooterData();
@endphp

<footer class="footer">
  <div class="footer-top py-5">
    <div class="container-fluid">
      <div class="row">
        <!-- Footer Logo and Description -->
        <div class="col-xxl-4 col-xl-4 col-md-6 col-sm-12 mb-4">
          <div class="footer-logo mb-4">
            @include('frontend::components.partials.logo')
          </div>
          <p class="font-size-14">
            TI Channel is a platform dedicated to spreading the message of peace, knowledge, and understanding of Islam. Through inspiring content, thought-provoking discussions, and authentic teachings, it aims to educate and connect people with the true essence of Islam, fostering unity and harmony in communities worldwide.
          </p>
          <div class="mt-4">
            <p class="mb-2 font-size-14" id="email-us" style="cursor: pointer;" onclick="copyText('info@tihd.tv')">{{ __('frontend.email_us') }}: info@tihd.tv</p>
            <p class="m-0 font-size-14" id="helpline-number" style="cursor: pointer;" onclick="copyText('+8809647123456')">{{ __('frontend.helpline_number') }}: +8809647123456</p>

            <!-- Snackbar -->
            <div class="snackbar" id="snackbar">
              <div class="d-flex justify-content-around align-items-center">
                <p class="mb-0" id="snackbar-message"></p>
                <a href="#" class="dismiss-link text-decoration-none text-success" onclick="dismissSnackbar(event)">Dismiss</a>
              </div>
            </div>
          </div>
        </div>

        <!-- Useful Links -->
        <div class="col-xxl-4 col-xl-4 col-md-6 col-sm-12 mb-4">
          <h4 class="footer-title font-size-18 mb-4">{{ __('frontend.usefull_links') }}</h4>
          <ul class="list-unstyled footer-menu column-count-2">
            @foreach($footerData['pages'] as $page)
              <li class="mb-2">
                <a href="{{ route('page.show', ['slug' => $page->slug]) }}">{{ $page->name }}</a>
              </li>
            @endforeach
            <li class="mb-2">
              <a href="{{ route('faq') }}">{{ __('frontend.faq') }}</a>
            </li>
          </ul>
        </div>

        <!-- Download App -->
        <div class="col-xxl-4 col-xl-4 col-md-6 col-sm-12 mb-4">
          <h4 class="footer-title font-size-18 mb-4">{{ __('frontend.download_app') }}</h4>
          <p class="mb-4">Download our app for instant access to engaging series, insightful talk shows, and inspiring videos! Stay connected and explore content that uplifts and enlightens—anytime, anywhere.</p>
          <ul class="app-icon list-inline d-flex align-items-center gap-3">
            @if($footerData['play_store_url'])
              <li>
                <a href="{{ $footerData['play_store_url'] }}" target="_blank" class="btn btn-link p-0">
                  <img src="{{ asset('img/web-img/play_store.png') }}" alt="play store" class="img-fluid">
                </a>
              </li>
            @endif
            @if($footerData['app_store_url'])
              <li>
                <a href="{{ $footerData['app_store_url'] }}" target="_blank" class="btn btn-link p-0">
                  <img src="{{ asset('img/web-img/app_store.png') }}" alt="app store" class="img-fluid">
                </a>
              </li>
            @endif
          </ul>
          <!-- @if($footerData['play_store_url'])
          <button id="installButton" class="btn btn-primary mt-3" style="display:none;">Install App</button>
          @endif -->
        </div>
      </div>
    </div>
  </div>

  <div class="footer-bottom py-3">
    <div class="container-fluid">
      <div class="text-center">
        © {{ now()->year }} <span>{{ env('APP_NAME') }}</span>. All rights reserved.
      </div>
    </div>
  </div>
</footer>

@include('frontend::components.partials.footer-sticky-menu')

<!-- Scripts -->
<script>
  // Copy to clipboard
  function copyText(text) {
    var textarea = document.createElement('textarea');
    textarea.value = text;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);

    // Show snackbar
    var snackbar = document.getElementById('snackbar');
    var snackbarMessage = document.getElementById('snackbar-message');
    snackbarMessage.textContent = 'Copied to clipboard: ' + text;
    snackbar.style.display = 'block';

    setTimeout(function() {
      snackbar.style.display = 'none';
    }, 3000);
  }

  function dismissSnackbar(event) {
    event.preventDefault();
    document.getElementById('snackbar').style.display = 'none';
  }

  // PWA install
  let deferredPrompt;
  window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    const installButton = document.getElementById('installButton');
    if(installButton) installButton.style.display = 'inline-block';

    installButton.addEventListener('click', () => {
      deferredPrompt.prompt();
      deferredPrompt.userChoice.then((choiceResult) => {
        deferredPrompt = null;
      });
    });
  });
</script>

<style>
  .snackbar {
    position: fixed;
    bottom: 50px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #323232;
    color: white;
    padding: 20px;
    border-radius: 5px;
    display: none;
    font-size: 14px;
    min-width: 320px;
    max-width: 90%;
    word-wrap: break-word;
    text-align: center;
  }

  .dismiss-link { cursor: pointer; margin-left: 10px; }

  @media (max-width: 576px) {
    .snackbar { font-size: 12px; padding: 15px; min-width: 260px; }
  }
  @media (min-width: 576px) and (max-width: 768px) {
    .snackbar { font-size: 13px; padding: 18px; min-width: 280px; }
  }
  @media (min-width: 992px) {
    .snackbar { font-size: 16px; max-width: 50%; }
  }
</style>
