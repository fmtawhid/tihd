<x-auth-layout>
  <x-slot name="title">
    @lang('Login')
  </x-slot>

<style>
    .login-wrapper {
        min-height: 100vh; /* পুরো স্ক্রিন ভরে রাখবে */
        display: flex;
        justify-content: center;
        align-items: center;
        background: transparent; /* পেজ ব্যাকগ্রাউন্ড transparent */
    }

    .login-card {
        width: 50%;
        background: transparent; /* Card transparent */
    }

    @media (max-width: 992px) {
        .login-card {
            width: 90%; /* মোবাইলে ছোট হবে */
        }
    }
</style>

<div class="login-wrapper">
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden login-card">
        <div class="row g-0" style="background: #121D24;">
            
            <!-- Left Side: Logo --> 
            <div class="col-md-6 d-flex flex-column justify-content-center align-items-center p-5">
                <a href="{{ url('/') }}">
                    <x-application-logo style="max-width: 200px;" />
                </a>
                <h2 class="mt-4">{{ __('Welcome Back ') }}</h2>
                <p class="text-muted">{{ __('To our Dashboard') }}</p>
            </div>

            <!-- Right Side: Login Form -->
            <div class="col-md-6 p-5">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Social Login -->
                <x-auth-social-login />

                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                <form method="POST" action="{{ $url ?? route('admin-login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <x-label for="email" :value="__('frontend.email')" />
                        <x-input id="email" type="email" name="email" 
                            placeholder="{{__('frontend.enter_email')}}" 
                            :value="old('email')" required autofocus />
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <x-label for="password" :value="__('frontend.password')" />
                        <x-input id="password" type="password" name="password" 
                            placeholder="{{__('messages.enter_password')}}" required 
                            autocomplete="current-password" />
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-3 form-check">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <label for="remember_me" class="form-check-label">
                            {{ __('frontend.remember_me') }}
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between align-items-center">
                        @if (Route::has('password.request'))
                            <a class="small text-muted" href="{{ route('password.request') }}">
                                {{ __('frontend.forgot_password') }}
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            {{ __('frontend.login') }}
                        </button>
                    </div>
                </form>

                @if (Route::has('register'))
                    <p class="text-center text-muted mt-4">
                        {{ __('frontend.no_account') }}
                        <a href="{{ route('register') }}" class="text-decoration-underline">
                            {{ __('frontend.register') }}
                        </a>
                    </p>
                @endif
            </div>

        </div>
    </div>
</div>


  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>

    .select2-container--default .select2-selection--single .select2-selection__rendered{
      line-height: inherit;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow,
    .select2-container--default .select2-selection--single .select2-selection__clear,
    .select2-container--classic .select2-selection--single .select2-selection__arrow {
      height: 100%;
    }

    </style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script type="text/javascript">
     window.onload = function() {
       // getSelectedOption();
    };

    $(document).ready(function() {
            $('#SelectUser').select2({
                placeholder: "Select Role",
                minimumResultsForSearch: Infinity

            });
        });

 function disableButton(){
document.getElementById('submit-btn').classList.add('disabled');
document.getElementById('submit-btn').innerText = 'Login...';

}

    function getSelectedOption() {
        var selectElement = document.getElementById("SelectUser");
        var selectedOption = selectElement.options[selectElement.selectedIndex];

        if (selectedOption  && selectedOption.value !== "") {
            var optionText = selectedOption.textContent || selectedOption.innerText; // Get the text of the selected option
            var optionValue = selectedOption.value; // Get the value of the selected option

            var values = optionValue.split(",");
            var password = values[0];
            var email = values[1];

            domId('email').value =email;
            domId('password').value = password;

        } else {
          domId('email').value = "";
          domId('password').value = "";
        }
    }
    function domId (name) {
      return document.getElementById(name)
    }
    function setLoginCredentials(type) {
      domId('email').value = domId(type+'_email').textContent
      domId('password').value = domId(type+'_password').textContent
    }
  </script>
</x-auth-layout>
