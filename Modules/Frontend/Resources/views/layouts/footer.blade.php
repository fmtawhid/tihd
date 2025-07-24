
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
            <!-- Logo -->
            @include('frontend::components.partials.logo')
          </div>
          <p class="font-size-14">
         <!--   {{$footerData['short_description']}} -->
            TI Channel is a platform dedicated to spreading the message of peace, knowledge, and understanding of Islam. Through inspiring content, thought-provoking discussions, and authentic teachings, it aims to educate and connect people with the true essence of Islam, fostering unity and harmony in communities worldwide.
          </p>
          <div class="mt-4">
            <!-- <p class="mb-2 font-size-14">{{__('frontend.email_us')}}: info@tihd.tv
              <a href="mailto:info@tihd.tv" class="link-body-emphasis">
                info@tihd.tv
              </a>
            </p>
            <p class="m-0 font-size-14">{{__('frontend.helpline_number')}}: +8809647123456
              <a href="tel:+8809647123456" class="link-body-emphasis fw-medium">
                +8809647123456
              </a>
            </p> -->



            <p class="mb-2 font-size-14" id="email-us" style="cursor: pointer;" onclick="copyText('info@tihd.tv')">{{__('frontend.email_us')}}: info@tihd.tv</p>
<p class="m-0 font-size-14" id="helpline-number" style="cursor: pointer;" onclick="copyText('+8809647123456')">{{__('frontend.helpline_number')}}: +8809647123456</p>

<!-- Demo Toster -->
<div class="snackbar" id="snackbar">
    <div class="d-flex justify-content-around align-items-center">
        <p class="mb-0" id="snackbar-message"></p>
        <a href="#" class="dismiss-link text-decoration-none text-success" onclick="dismissSnackbar(event)">Dismiss</a>
    </div>
</div>

<script>
  function copyText(text) {
    var textarea = document.createElement('textarea');
    textarea.value = text;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);

    // Show snackbar message
    var snackbar = document.getElementById('snackbar');
    var snackbarMessage = document.getElementById('snackbar-message');
    snackbarMessage.textContent = 'Copied to clipboard: ' + text;

    // Display the snackbar for 3 seconds
    snackbar.style.display = 'block';
    setTimeout(function() {
      snackbar.style.display = 'none';
      // After snackbar disappears, show alert
      // alert('Copied to clipboard: ' + text);
    }, 3000); // Snackbar will disappear after 3 seconds
  }

  function dismissSnackbar(event) {
    event.preventDefault();
    var snackbar = document.getElementById('snackbar');
    snackbar.style.display = 'none';
  }
</script>

<style>
  /* Basic styles for snackbar */
  /* Basic styles for snackbar */
/* Basic styles for snackbar */
.snackbar {
  position: fixed;
  bottom: 50px;
  left: 50%;  /* Use 50% left for center alignment */
  transform: translateX(-50%);
  background-color: #323232;
  color: white;
  padding: 20px 20px;
  border-radius: 5px;
  display: none; /* Initially hidden */
  font-size: 14px;
  min-width: 320px; /* Ensure snackbar is at least 320px wide */
  max-width: 90%; /* Ensure snackbar doesn't exceed 90% of screen width */
  word-wrap: break-word; /* Allow wrapping of long text */
  text-align: center; /* Center the text */
}

.dismiss-link {
  cursor: pointer;
  margin-left: 10px;
}

/* Media Queries for Responsive Design */

/* For small screens */
@media (max-width: 576px) {
  .snackbar {
    font-size: 12px; /* Reduce font size for smaller screens */
    padding: 15px; /* Reduce padding for smaller screens */
    min-width: 260px; /* Slightly smaller minimum width on small screens */
  }
}

/* For medium screens */
@media (min-width: 576px) and (max-width: 768px) {
  .snackbar {
    font-size: 13px; /* Slightly smaller font for medium screens */
    padding: 18px; /* Adjust padding */
    min-width: 280px; /* Set a slightly higher minimum width on medium screens */
  }
}

/* For large screens */
@media (min-width: 992px) {
  .snackbar {
    font-size: 16px; /* Increase font size for larger screens */
    max-width: 50%; /* Limit width on large screens */
  }
}

</style>


















          </div>
        </div>

        <!-- Useful Links -->
        <div class="col-xxl-4 col-xl-4 col-md-6 col-sm-12 mb-4">
          <h4 class="footer-title font-size-18 mb-4">{{__('frontend.usefull_links')}}</h4>
          <ul class="list-unstyled footer-menu column-count-2">
            @foreach($footerData['pages'] as $page)
            <li class="mb-2">
              <a href="{{ route('page.show', ['slug' => $page->slug]) }}">{{ $page->name }}</a>
            </li>
            @endforeach
            <li class="mb-2">
              <a href="{{route('faq')}}">{{__('frontend.faq')}}</a>
            </li>
          </ul>
        </div>

        <!-- Download App -->
        <div class="col-xxl-4 col-xl-4 col-md-6 col-sm-12 mb-4">
          <h4 class="footer-title font-size-18 mb-4">{{__('frontend.download_app')}}</h4>
          <p class="mb-4">Download our app for instant access to engaging series, insightful talk shows, and inspiring videos! Stay connected and explore content that uplifts and enlightens—anytime, anywhere.</p>
          <ul class="app-icon list-inline d-flex align-items-center gap-3">
            @if($footerData['play_store_url'])
            <li>
             <!--  <button id='installButton' class="btn btn-link p-0">
                <img src="{{ asset('img/web-img/play_store.png') }}" alt="play store" class="img-fluid">
              </button>
            </li>  -->
            @endif
            @if($footerData['app_store_url'])
            <li>
             <!-- <a href="{{$footerData['app_store_url']}}" class="btn btn-link p-0" target="_blank">
                <img src="{{ asset('img/web-img/app_store.png') }}" alt="app store" class="img-fluid">
              </a>  -->
            </li>
            @endif
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="footer-bottom py-3">
    <div class="container-fluid">
      <div class="text-center">
        © {{ now()->year }} <span class=" ">{{ env('APP_NAME') }}</span>. All rights reserved.
      </div>
    </div>
  </div>
</footer>


<script>
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    // Show a custom install button
    const installButton = document.getElementById('installButton');
    installButton.style.display = 'block';

    installButton.addEventListener('click', () => {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted the PWA installation');
            } else {
                console.log('User dismissed the PWA installation');
            }
            deferredPrompt = null;
        });
    });
});

</script>


<!-- sticky footer -->
  @include('frontend::components.partials.footer-sticky-menu')
