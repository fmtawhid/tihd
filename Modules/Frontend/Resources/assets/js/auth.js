const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#password');
togglePassword.addEventListener('click', function () {
  const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
  password.setAttribute('type', type);
  this.classList.toggle('fa-eye-slash');
});

const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
const confirm_password = document.querySelector('#confirm_password');
if (toggleConfirmPassword) {

  toggleConfirmPassword.addEventListener('click', function () {
    const type_confirm = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
    confirm_password.setAttribute('type', type_confirm);
    this.classList.toggle('fa-eye-slash');
  });

}

const registerForm = document.querySelector('#registerForm');
const registerButton = document.querySelector('#register-button');
const errorMessage = document.querySelector('#error_message');

const baseUrl = document.querySelector('meta[name="base-url"]').getAttribute('content');



if (registerForm) {
  registerForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    const isValid = validateRegisterForm(); // Manually validate the form
    if (!isValid) {
      return;
    }
    toggleRegisterButton(true, registerButton);
    errorMessage.textContent = '';

    try {
      const formData = new FormData(this);
      const response = await fetch(`${baseUrl}/api/register?is_ajax=1` , {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      });

      const data = await response.json();

      if (!response.ok) {
        handleValidationErrors(data.errors);
      }

      if (data.status==true) {


        try {
          const formData = new FormData(this);
          const response = await fetch(`${baseUrl}/api/login?is_ajax=1`, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
          });

          const data = await response.json();

          if (data.status == true) {
            window.location.href = `${baseUrl}`;
          }
        } catch (error) {
          if (error.message !== 'Validation Error') {
          }
        }

      } else {
        errorMessage.textContent = data.message;
      }
    } catch (error) {
      errorMessage.textContent = 'An error occurred. Please try again later.';
    } finally {
      toggleRegisterButton(false, registerButton);
    }
  });
}

function toggleRegisterButton(isSubmitting, button) {
  const registrationText = button.getAttribute('data-login-text') || 'Sign Up';
  button.textContent = isSubmitting ? 'Sign Up...' : registrationText;
  button.disabled = isSubmitting;
}

function validateRegisterForm() {
  let isValid = true;

  const firstName = registerForm.querySelector('input[name="first_name"]');
  const lastName = registerForm.querySelector('input[name="last_name"]');
  const email = registerForm.querySelector('input[name="email"]');
  const password = registerForm.querySelector('input[name="password"]');
  const confirmPassword = registerForm.querySelector('input[name="confirm_password"]');


  if (!firstName.value.trim()) {
    showValidationError(firstName, 'First Name field is required.');
    isValid = false;
  } else {
    clearValidationError(firstName);
  }

  if (!lastName.value.trim()) {
    showValidationError(lastName, 'Last Name field is required.');
    isValid = false;
  } else {
    clearValidationError(lastName);
  }

  if (email && email.required) {
    if (email.value.trim() === '') {
      showValidationError(email, 'Email field is required.');
      isValid = false;
    } else if (!validateEmail(email.value)) {
      showValidationError(email, 'Enter a valid Email Address.');
      isValid = false;
    } else {
      clearValidationError(email);
    }
  }

  if (!password.value.trim()) {

    showValidationError(password, 'Password field is required.');
    isValid = false;
  } else if (password.value.length < 6) {

    showValidationError(password, 'Password must be at least 6 characters long.');
    isValid = false;
  } else {
    clearValidationError(password);
  }

  if (password.value.length > 6 && password.value !== confirmPassword.value) {
    showValidationError(confirmPassword, 'Passwords and confirm password do not match.');
    isValid = false;
  } else {
    clearValidationError(confirmPassword);
  }

  return isValid;
}


const loginForm = document.querySelector('#login-form');

if (loginForm) {
  const loginButton = document.querySelector('#login-button');
  const loginError = document.querySelector('#login_error_message');

  loginForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    const isValid = validateloginForm(); // Manually validate the form
    if (!isValid) {
      return;
    }
    toggleLoginButton(true, loginButton);
    loginError.textContent = '';

    try {
      const formData = new FormData(this);
      const response = await fetch(`${baseUrl}/api/login?is_ajax=1`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      });

      const data = await response.json();

      if (!response.ok) {
        handleValidationErrors(data.errors);
      }
      if (data.status == true) {
        window.location.href = `${baseUrl}`;
      } else {

        loginError.textContent = data.message


      }
    } catch (error) {
      if (error.message !== 'Validation Error') {
      }
    } finally {
      toggleLoginButton(false, loginButton);
    }
  });

  function validateloginForm() {
    let isValid = true;
    const emailField = loginForm.querySelector('input[name="email"]');
    const passwordField = loginForm.querySelector('input[name="password"]');

    if (emailField && emailField.required) {
      if (emailField.value.trim() === '') {
        showValidationError(emailField, 'Email field is required.');
        isValid = false;
      } else if (!validateEmail(emailField.value)) {
        showValidationError(emailField, 'Enter a valid Email Address.');
        isValid = false;
      } else {
        clearValidationError(emailField);
      }
    }

    if (passwordField && passwordField.value.trim() === '') {
      passwordField.classList.add('is-invalid');
      isValid = false;
    } else {
      passwordField.classList.remove('is-invalid');
    }

    return isValid;
  }


  function toggleLoginButton(isSubmitting, button) {
    const loginText = button.getAttribute('data-login-text') || 'Login';
    button.textContent = isSubmitting ? 'Sign In...' : loginText;
    button.disabled = isSubmitting;
  }

}

function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}


function showValidationError(input, message) {
  const container = input.closest('.input-group');
  const errorFeedback = container.querySelector('.invalid-feedback');

  if (errorFeedback) {
    errorFeedback.textContent = message;
    input.classList.add('is-invalid');
  }
}

function clearValidationError(input) {

  const container = input.closest('.input-group');
  const errorFeedback = container.querySelector('.invalid-feedback');

  if (errorFeedback) {
    errorFeedback.textContent = '';
    input.classList.remove('is-invalid');
  }
}




