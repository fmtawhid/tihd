@extends('frontend::layouts.master')
@section('content')
<div class="page-title">
    <h4 class="m-0 text-center">{{__('frontend.edit_profile')}}</h4>
</div>
<div class="section-spacing-bottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 ps-md-0">
                <ul class="nav nav-tabs flex-column gap-4">
                    <li class="nav-item">
                        <a class="nav-link active p-3 text-center" data-bs-toggle="pill" href="#editProfile">
                            <h6 class="m-0">{{__('frontend.update_profile')}}</h6>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-3 text-center" data-bs-toggle="pill" href="#changePassword">
                            <h6 class="m-0">{{__('frontend.profiles')}}</h6>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-9 col-md-9 mt-lg-0 mt-5">
                <div class="tab-content">
                    <div class="tab-pane active fade show" id="editProfile" role="tabpanel">
                        <div class="card">
                            <div class="edit-profile-content">
                                <div class="edit-profile-details">
                                    <div class="bg-body rounded ">
                                        <h6 class="mb-3">{{__('frontend.profiles_details')}}</h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="select-profile-card text-center position-relative">
                                                    <!-- Profile Image -->
                                                    <img id="profileImage" src="{{setBaseUrlWithFileName($user->file_url) ?? setDefaultImage()}}" class="img-fluid rounded-circle object-cover"
                                                        alt="select-profile-image" style="cursor: pointer; width: 150px; height: 150px;">

                                                    <!-- Hidden file input -->
                                                    <input type="file" id="profileImageInput" class="d-none" accept="image/*" onchange="previewImage(event)">

                                                    <!-- Pencil icon -->
                                                    <i class="ph ph-pencil pencil-icon" onclick="triggerFileInput()"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-9 mt-md-0 mt-4">
                                                <form id="editProfileDetail">
                                                    @csrf
                                                    <div class="input-group mb-3">
                                                        <span class="input-style-text input-group-text px-0"><i class="ph ph-user"></i></span>
                                                        <input type="text" name="first_name" class="form-control input-style-box" value="{{ $user->first_name }}" placeholder="{{__('frontend.enter_fname')}}" >
                                                        <div class="invalid-feedback" id="first_name_error">First Name field is required</div>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text input-style-text px-0"><i class="ph ph-user"></i></span>
                                                        <input type="text" name="last_name" class="form-control input-style-box" value="{{ $user->last_name }}" placeholder="{{__('frontend.enter_lname')}}">
                                                        <div class="invalid-feedback" id="last_name_error">Last Name field is required</div>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text input-style-text px-0"><i class="ph ph-envelope"></i></span>
                                                        <input type="email" name="email" class="form-control input-style-box" value="{{ $user->email }}" @if ($user->login== 'google') readonly @endif>
                                                        <div class="invalid-feedback" id="email_error">Email is required</div>
                                                    </div>
                                                    <div class="input-group mb-3 d-flex flex-nowrap">
                                                        <span class="input-group-text input-style-text px-0"><i class="ph ph-phone"></i></span>
                                                        <input type="tel" class="form-control input-style-box" value="{{ $user->mobile }}" id="mobileInput"  @if ($user->login== 'otp') readonly @endif>
                                                        <div class="invalid-feedback" id="mobile_error">Mobile number is required</div>
                                                    </div>
                                                    <div class="input-group mb-3 flex-nowrap">
                                                     <span class="input-group-text input-style-text px-0"><i class="ph ph-gender-neuter"></i></span>
                                                        <div class="d-flex flex-nowrap align-items-center input-style-box w-100 gap-2 px-2">
                                                            <label class="form-check form-check-inline cursor-pointer">
                                                                <input class="form-check-input" type="radio" name="gender" id="male" value="male"
                                                                    {{ old('gender', isset($user) ? $user->gender : 'male') == 'male' ? 'checked' : '' }}>
                                                                <span class="form-check-label">{{ __('messages.lbl_male') }}</span>
                                                            </label>
                                                            <label class="form-check form-check-inline cursor-pointer">
                                                                <input class="form-check-input" type="radio" name="gender" id="female" value="female"
                                                                    {{ old('gender', isset($user) ? $user->gender : 'male') == 'female' ? 'checked' : '' }}>
                                                                <span class="form-check-label">{{ __('messages.lbl_female') }}</span>
                                                            </label>
                                                            <label class="form-check form-check-inline cursor-pointer">
                                                                <input class="form-check-input" type="radio" name="gender" id="other" value="other"
                                                                    {{ old('gender', isset($user) ? $user->gender : 'male') == 'other' ? 'checked' : '' }}>
                                                                <span class="form-check-label">{{ __('messages.lbl_other') }}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text input-style-text px-0"><i class="ph ph-calendar"></i></span>
                                                        <input type="date" name="date_of_birth" class="form-control input-style-box"
                                                            value="{{ old('date_of_birth', isset($user) && $user->date_of_birth  ? date('Y-m-d', strtotime($user->date_of_birth)) : '') }}"
                                                            max="{{ date('Y-m-d') }}" placeholder="{{ __('placeholder.lbl_user_date_of_birth') }}" required>
                                                        <div class="invalid-feedback" id="date_of_birth_error">Date of Birth field is required</div>
                                                    </div>
                                                    <div class="text-end">
                                                        <button type="button" id="updateProfileBtn" class="btn btn-primary mt-5">{{__('frontend.update')}}</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal fade" id="SuccessfullyUpdateProfileModal" tabindex="-1" aria-labelledby="SuccessfullyUpdateProfileModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center">
                                                            <span style="color: white; font-size: 18px;">
                                                               {{__('frontend.profile_updated')}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="changePassword" role="tabpanel">
                        <div class="card user-login-card p-5">
                            <div class="edit-profile-content">
                                <h6>{{__('frontend.profiles')}}</h6>
                                <div class="row row-cols-xl-4 row-cols-md-3 row-cols-sm-2 row-cols-1 gy-5" id="profileList">
                                    @foreach ($userProfile->toArray(request()) as $profile)

                                        @if($profile['is_active']==1)

                                            <div class="col">
                                                <div  class="card  bg-body profil-card border border-primary">
                                                    <div class="card-body  rounded text-center">
                                                        <div class="profile-card-image">


                                                            <img id="profile_image_{{ $profile['id'] }}"
                                                            src="{{ !empty($profile['avatar']) ? $profile['avatar'] : asset('path/to/default/image.png') }}"
                                                            alt="profile-image">
                                                        </div>
                                                        <h5 class="mt-3 mb-4 font-size-18" id="profile_name_{{ $profile['id'] }}">{{ $profile['name'] }}</h5>
                                                        <button class="btn p-0 h6 mb-0" data-bs-toggle="modal"
                                                            data-bs-target="#selectProfileModal" data-type="update">
                                                            <span class="d-flex align-items-center gap-2" onclick=" editProfile( {{ $profile['id'] }})">
                                                                <span><i class="ph ph-pencil-simple-line"></i></span>
                                                                <span>{{__('frontend.edit')}}</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col" >
                                                <div class="card bg-body profil-card"  >
                                                    <div class="card-body rounded text-center" onclick="SelectProfile({{ $profile['id'] }})">
                                                        <div class="profile-card-image" >
                                                            <img id="profile_image_{{ $profile['id'] }}" src="{{ $profile['']?? setDefaultImage()}}" alt="profile-image">
                                                        </div>
                                                        <h5 class="mt-3 mb-4 font-size-18" id="profile_name_{{ $profile['id'] }}">{{ $profile['name'] }}</h5>
                                                        <button class="btn p-0 h6 mb-0" data-bs-toggle="modal" data-type="update"
                                                            data-bs-target="#selectProfileModal" >
                                                            <span class="d-flex align-items-center gap-2" onclick=" event.stopPropagation(); editProfile( {{ $profile['id']  }})">
                                                                <span><i class="ph ph-pencil-simple-line"></i></span>
                                                                <span>{{__('frontend.edit')}}</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach

                                    <div class="col">
                                        <div class="card profil-card cursor-pointer" data-bs-toggle="modal"
                                            data-bs-target="#selectProfileModal" data-type="add">
                                            <div
                                                class="card-body bg-body rounded text-center d-flex flex-column align-items-center justify-content-center">
                                                <div class="profile-card-add-user bg-dark">
                                                    <i class="ph ph-plus"></i>
                                                </div>
                                                <h5 class="mt-3 mb-0 font-size-18">{{__('frontend.add_user')}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="updateProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content position-relative">
            <div class="modal-body user-login-card m-0 p-4 position-relative">
                <button type="button" class="btn btn-primary custom-close-btn rounded-2" data-bs-dismiss="modal">
                    <i class="ph ph-x text-white fw-bold align-middle"></i>
                </button>
                <h6>{{__('frontend.edit_profile_details')}}</h6>
                <form class="editProfileDetail">
                    <div class="input-group mb-3">
                        <span class="input-style-text input-group-text px-0"><i class="ph ph-user"></i></span>
                        <input type="text" name="first_name" class="form-control input-style-box" value="{{ $user->first_name }}">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text input-style-text px-0"><i class="ph ph-user"></i></span>
                        <input type="text" name="last_name" class="form-control input-style-box" value="{{ $user->last_name }}">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text input-style-text px-0"><i class="ph ph-envelope"></i></span>
                        <input type="text" name="email" class="form-control input-style-box" value="{{ $user->email }}">
                    </div>
                </form>
                <div class="d-flex align-items-center justify-content-center">
                    <button class="btn btn-primary mt-5" data-bs-toggle="modal" data-bs-target="#SuccessfullyUpdateProfileModal">{{__('frontend.update')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade add-profile-modal" id="selectProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content position-relative">
          <button type="button" class="btn btn-primary custom-close-btn rounded-2" data-bs-dismiss="modal">
              <i class="ph ph-x text-white fw-bold align-middle"></i>
          </button>
          <form id="ProfileDetail" action="Post" class="requires-validation" data-toggle="validator" novalidate >
            <div class="modal-body text-center">

                <input type="hidden" name="profile_id" id="profile_id" value=""/>
                <div class="select-profile-slider d-flex align-items-center gap-3 ">
                    <!-- Example for the first image -->

                   <div class="slick-item">
                    <label class="select-profile-card">
                        <input type="radio" name="profile_image" value="{{ asset('/img/web-img/user-img.png')}}" class="d-none" />
                        <img src="{{ asset('/img/web-img/user-img.png')}}" class="select-profile-image" id="profile_image" alt="select-profile-image">
                        <input type="file" id="profileFileImageInput" class="d-none" accept="image/*" onchange="previewProfileImage(event)">
                        <i class="ph ph-pencil pencil-icon" onclick="triggerProfileFileInput()"></i>
                    </label>

                   </div>
                   <div class="slick-item">
                      <label class="select-profile-card">
                          <input type="radio" name="profile_image" value="{{ asset('/img/web-img/user-img.png')}}" class="d-none" />
                          <img src="{{ asset('/img/web-img/user-img.png')}}" class="select-profile-image" alt="select-profile-image">
                      </label>
                   </div>
                  <div class="slick-item">
                      <label class="select-profile-card">
                          <input type="radio" name="profile_image" value="{{ asset('/img/web-img/user-img.png')}}" class="d-none" id="profile_image_value" checked />
                          <img src="{{ asset('/img/web-img/user-img.png')}}" class="select-profile-image" alt="select-profile-image">
                      </label>
                  </div>
                  <div class="slick-item">
                      <label class="select-profile-card">
                          <input type="radio" name="profile_image" value="{{ asset('/img/web-img/user-img.png')}}" class="d-none" />
                          <img src="{{ asset('/img/web-img/user-img.png')}}" class="select-profile-image" alt="select-profile-image">
                      </label>
                  </div>
                  <div class="slick-item">
                      <label class="select-profile-card">
                          <input type="radio" name="profile_image" value="{{ asset('/img/web-img/user-img.png')}}" class="d-none" />
                          <img src="{{ asset('/img/web-img/user-img.png')}}" class="select-profile-image" alt="select-profile-image">
                      </label>
                  </div>
              </div>
              <div class="pt-4 mt-4 user-login-card">

                      <div class="input-group mb-3">
                          <span class="input-style-text input-group-text px-0"><i class="ph ph-user"></i></span>
                          <input type="text" name="profile_first_name" class="form-control input-style-box" placeholder="{{__('frontend.enter_name')}}" id="profile_first_name" required>
                          <div class="invalid-feedback" id="password-error">Name field is required.</div>
                      </div>
                      <div class="mt-5 pt-4">
                          <button type="submit"  id="update-profile" class="btn btn-primary">{{__('messages.add')}}</button>
                      </div>

              </div>
          </div>

                </div>
            </div>
        </form>
      </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>

<style>
        .iti__country-list {
            max-height: 200px !important;
            overflow-y: auto !important;
            width: 60% !important;
            position: relative;
            width: 60% !important;
            left: 20% !important;
            right: 20% !important;
            top: 60%;
        }
        /* TV View (max-width: 1199px) */
        @media (max-width: 1199px) {
            .iti__country-list {
                max-height: 180px !important;
                width: 60% !important;
                left: 20% !important;
                right: 20% !important;
                top: 60%;
                padding: 10px 0 !important;
            }

            .iti-mobile .iti--container {
                left: 10% !important;
                right: 10% !important;
                top: 50% !important;
                transform: translateY(-50%);
            }

            /* Scroll Up and Down buttons for TV */
            .scroll-btn {
                width: 28px;
                height: 28px;
                font-size: 16px;
            }

            .scroll-btn-down {
                width: 28px;
                height: 28px;
                font-size: 16px;
            }
        }
        /* Mobile View (min-width: 320px) */
        @media (max-width: 576px) {
            .iti__country-list {
                max-height: 150px !important;
                width: 90% !important;
                left: 16px !important;
                right: 0 !important;
                padding: 10px 0 !important;
            }

            .iti-mobile .iti--container {
                left: 0 !important;
                right: 0 !important;
                top: 50% !important;
                transform: translateY(-50%);
            }

            /* Scroll Up and Down buttons for mobile */
            .scroll-btn {
                width: 25px;
                height: 25px;
                font-size: 14px;
            }

            .scroll-btn-down {
                width: 25px;
                height: 25px;
                font-size: 14px;
            }
        }



        /* Scrollbar styling */
        .iti__country-list::-webkit-scrollbar {
            width: 18px;
        }

        .iti__country-list::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .iti__country-list::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .iti__country-list::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Scroll Up and Down buttons */
        .scroll-btn {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 30px;
            background-color: #888;
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            z-index: 2;
        }

        .scroll-btn-down {
            bottom: 0;
            top: auto;
        }

        .scroll-btn:hover {
            background-color: #555;
        }

        @media (max-width: 991px) {
            .editproficefortv {
                display: none;
            }
        }


    </style>
<script>
 

    var input = document.querySelector("#mobileInput");
    var iti = window.intlTelInput(input, {
        initialCountry: "bd",  // Automatically detect user's country
        separateDialCode: true,  // Show the country code separately
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"  // To handle number formatting
    });


function triggerProfileFileInput() {
    document.getElementById('profileFileImageInput').click();
}

// Function to preview the selected image
function previewProfileImage(event) {
    const reader = new FileReader();
    const fileInput = event.target;

    reader.onload = function() {
        const previewImage = document.getElementById('profile_image');
        previewImage.src = reader.result; // Update the image preview
    };

    reader.readAsDataURL(fileInput.files[0]);
}


      function triggerFileInput() {
        document.getElementById('profileImageInput').click();
    }
    function previewImage(event) {
        const image = document.getElementById('profileImage');
        image.src = URL.createObjectURL(event.target.files[0]);
    }

    document.getElementById('profileImage').addEventListener('click', triggerFileInput);
    const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');

    document.addEventListener('DOMContentLoaded', function () {

        $(document).ready(function() {

            $('#updateProfileBtn').on('click', function(e) {
                e.preventDefault();

                $('.invalid-feedback').hide();
                $('input').removeClass('is-invalid');

                let valid = true;

                const fieldsToValidate = [
                    {
                        name: 'first_name',
                        errorElement: '#first_name_error'
                    },
                    {
                        name: 'last_name',
                        errorElement: '#last_name_error'
                    },
                    {
                        name: 'date_of_birth',
                        errorElement: '#date_of_birth_error'
                    }
                ];

                fieldsToValidate.forEach(field => {
                    const value = $(`input[name="${field.name}"]`).val().trim();
                    if (!value) {
                        $(field.errorElement).show();
                        $(`input[name="${field.name}"]`).addClass('is-invalid');
                        valid = false;
                    }
                });

                const mobileInput = $('#mobileInput');

                    const mobileValue = mobileInput.val().trim();
                    if (!mobileValue) {
                        $('#mobileInput').addClass('is-invalid');
                        $('#mobile_error').show().text('Mobile number is required');
                        valid = false;
                    } else {
                        $('#mobile_error').hide();
                    }

                if (!valid) {
                    return;
                }

                var number = iti.getNumber()


                var formData = new FormData($('#editProfileDetail')[0]);

                formData.append('mobile', number);

                var imageFile = $('#profileImageInput')[0].files[0];
                if (imageFile) {
                    formData.append('file_url', imageFile);
                }

                var $btn = $(this);
                $btn.prop('disabled', true).text('Updating...');

                $.ajax({
                    url: `${baseUrl}/api/update-profile`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'Authorization': 'Bearer ' + '{{ auth()->user()->api_token }}'
                    },
                    success: function(response) {
                        if (response.status === true) {
                            $('input[name="first_name"]').val(response.data.first_name);
                            $('input[name="last_name"]').val(response.data.last_name);
                            $('input[name="email"]').val(response.data.email);
                            $('input[name="mobile"]').val(response.data.mobile);
                            $('input[name="date_of_birth"]').val(response.data.date_of_birth);

                          //  const image = document.getElementById('profileImage');
                         //   image.src =setBaseUrlWithFileName(response.data.file_url);

                            $('input[name="gender"][value="' + response.data.gender + '"]').prop('checked', true);

                            window.successSnackbar(response.message)

                            $btn.prop('disabled', false).text('Update');

                        } else {
                            window.successSnackbar('Error updating profile.')
                            $btn.prop('disabled', false).text('Update');

                        }
                    },
                    error: function(xhr, status, error) {
                  var response = JSON.parse(xhr.responseText);

                  if (response.message) {

                      window.successSnackbar(response.message);
                  } else if (response.errors && response.errors.mobile) {

                      window.successSnackbar(response.errors.mobile[0]);
                  }

                  $btn.prop('disabled', false).text('Update');
                 }
                });
            });
        });

        $(document).ready(function () {
            const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');

            const apiUrl = `${baseUrl}/api/save-userprofile`;

    $("#ProfileDetail").on("submit", function (e) {
        e.preventDefault();
        let isValid = true;

        const nameField = $("#profile_first_name");
        if (nameField.val().trim() === "") {
            nameField.addClass("is-invalid");
            isValid = false;
        } else {
            nameField.removeClass("is-invalid");
        }

        const selectedImage = $('input[name="profile_image"]:checked').val();
        const profileId = $("#profile_id").val();

         var formData = new FormData();

         if (!isValid) return;

         formData.append('id', profileId);
         formData.append('avatar', selectedImage);
         formData.append('name', nameField.val());

         var imageFile = $('#profileFileImageInput')[0].files[0];
         if (imageFile) {
             formData.append('file_url', imageFile);
         }
                // Make an AJAX request to save the profile
                $.ajax({
                    url: apiUrl, // Replace with your actual API endpoint
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {

                        $("#profileList").empty();

                        response.data.forEach(function (profile) {
                            let profileHtml = '';

                            if (profile.is_active == 1) {
                                profileHtml = `
                                <div class="col">
                                    <div class="card bg-body profil-card border border-primary" >
                                        <div class="card-body  rounded text-center">
                                            <div class="profile-card-image">
                                                <img id="profile_image_${profile.id}" src="${profile.avatar}" alt="profile-image">
                                            </div>
                                            <h5 class="mt-3 mb-4 font-size-18" id="profile_name_${profile.id}">${profile.name}</h5>
                                            <button class="btn p-0 h6 mb-0" data-bs-toggle="modal" data-type="update" data-bs-target="#selectProfileModal">
                                                <span class="d-flex align-items-center gap-2" onclick="event.stopPropagation(); editProfile(${profile.id})">
                                                    <span><i class="ph ph-pencil-simple-line"></i></span>
                                                    <span>Edit</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>`;
                            } else {
                                profileHtml = `
                                <div class="col" >
                                    <div class="card bg-body profil-card" onclick="SelectProfile(${profile.id})" >
                                        <div class="card-body  rounded text-center">
                                            <div class="profile-card-image" >
                                                <img id="profile_image_${profile.id}" src="${profile.avatar}" alt="profile-image">
                                            </div>
                                            <h5 class="mt-3 mb-4 font-size-18" id="profile_name_${profile.id}">${profile.name}</h5>
                                            <button class="btn p-0 h6 mb-0" data-bs-toggle="modal" data-type="update" data-bs-target="#selectProfileModal">
                                                <span class="d-flex align-items-center gap-2" onclick="event.stopPropagation(); editProfile(${profile.id})">
                                                    <span><i class="ph ph-pencil-simple-line"></i></span>
                                                    <span>Edit</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>`;
                            }

                            $("#profileList").append(profileHtml);
                        });

                        // Append the "Add User" card at the end of the profile list
                        const addUserHtml = `
                            <div class="col">
                                <div class="card profil-card cursor-pointer" data-bs-toggle="modal"  data-type="add" data-bs-target="#selectProfileModal">
                                    <div class="card-body bg-body rounded text-center d-flex flex-column align-items-center justify-content-center">
                                        <div class="profile-card-add-user bg-dark">
                                            <i class="ph ph-plus"></i>
                                        </div>
                                        <h5 class="mt-3 mb-0 font-size-18">Add Profile</h5>
                                    </div>
                                </div>
                            </div>`;

                        $("#profileList").append(addUserHtml);
                        window.successSnackbar(response.message)
                        // Close the modal
                        $("#selectProfileModal").modal('hide');
                    },
                    error: function (xhr, status, error) {
                        if(xhr.status){
                            window.successSnackbar(xhr.responseJSON.error)

                        }else{
                            window.successSnackbar('Something went wrong!')
                        }
                        $("#selectProfileModal").modal('hide');

                    }
                });

                $("#profile_id").val("");
            });

            // Reset validation feedback when modal is closed
            $('#selectProfileModal').on('hidden.bs.modal', function () {
                $("#ProfileDetail")[0].reset();
                $(".is-invalid").removeClass("is-invalid");

            });
        });

    });

    function editProfile(id) {

        const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
        const apiUrl = `${baseUrl}/api/get-userprofile/${id}`;

        fetch(apiUrl, {
            method: 'GET',
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(response => {

            document.getElementById('profile_id').value = response.data.id;
            document.getElementById('profile_first_name').value = response.data.name;
            const profileImageValue = response.data.avatar; // Get the profile image value from the API
            const modalImage = document.getElementById('profile_image');
            document.getElementById('profile_image_value').value = profileImageValue;
            modalImage.setAttribute('src', profileImageValue);
            $('#selectProfileModal').modal('show');


        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    }

    function SelectProfile(id){

        const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
        const apiUrl = `${baseUrl}/api/select-userprofile/${id}`;

        fetch(apiUrl, {
            method: 'GET',
        })
        .then(response => {
            if (!response.ok) {
                 throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(response => {
            // window.successSnackbar(response.message)
            $("#profileList").empty();

                response.data.forEach(function (profile) {
                            let profileHtml = '';

                            if(profile.is_active == 1) {
                                profileHtml = `
                                <div class="col">
                                    <div class="card bg-body profil-card border border-primary">
                                        <div class="card-body  rounded text-center">
                                            <div class="profile-card-image">
                                                <img id="profile_image_${profile.id}" src="${profile.avatar}" alt="profile-image">
                                            </div>
                                            <h5 class="mt-3 mb-4 font-size-18" id="profile_name_${profile.id}">${profile.name}</h5>
                                            <button class="btn p-0 h6 mb-0" data-bs-toggle="modal" data-type="update" data-bs-target="#selectProfileModal">
                                                <span class="d-flex align-items-center gap-2" onclick="event.stopPropagation(); editProfile(${profile.id})">
                                                    <span><i class="ph ph-pencil-simple-line"></i></span>
                                                    <span>Edit</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>`;
                            } else {
                                profileHtml = `
                                <div class="col" >
                                    <div class="card bg-body profil-card"  onclick="SelectProfile(${profile.id})">
                                        <div class="card-body  rounded text-center">
                                            <div class="profile-card-image">
                                                <img id="profile_image_${profile.id}" src="${profile.avatar}" alt="profile-image">
                                            </div>
                                            <h5 class="mt-3 mb-4 font-size-18" id="profile_name_${profile.id}">${profile.name}</h5>
                                            <button class="btn p-0 h6 mb-0" data-bs-toggle="modal" data-type="update" data-bs-target="#selectProfileModal">
                                                <span class="d-flex align-items-center gap-2" onclick="event.stopPropagation(); editProfile(${profile.id})">
                                                    <span><i class="ph ph-pencil-simple-line"></i></span>
                                                    <span>Edit</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>`;
                            }

                            $("#profileList").append(profileHtml);
                });


                // Append the "Add User" card at the end of the profile list
                const addUserHtml = `
                    <div class="col">
                        <div class="card profil-card cursor-pointer" data-bs-toggle="modal"  data-type="add" data-bs-target="#selectProfileModal">
                            <div class="card-body bg-body rounded text-center d-flex flex-column align-items-center justify-content-center">
                                <div class="profile-card-add-user bg-dark">
                                    <i class="ph ph-plus"></i>
                                </div>
                                <h5 class="mt-3 mb-0 font-size-18">Add Profile</h5>
                            </div>
                        </div>
                    </div>`;

                $("#profileList").append(addUserHtml);

        })
        .catch(error => {
            window.successSnackbar(error)

        });

    }


    document.addEventListener('DOMContentLoaded', function () {
        const selectProfileModal = document.getElementById('selectProfileModal');

        selectProfileModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const dataType = button.getAttribute('data-type');
            const updateButton = document.getElementById('update-profile');

            if (dataType === 'add') {
                $("#ProfileDetail")[0].reset();
                $("#profile_id").val('');
                updateButton.textContent = '{{__('messages.add')}}'; // Change to "Add"

            } else {

                updateButton.textContent = '{{__('frontend.update')}}'; // Default "Update"

            }
        });


    });



</script>
@endsection
