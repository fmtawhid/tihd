@extends('backend.layouts.app')

@section('content')
<x-back-button-component route="backend.seasons.index" />
<p class="text-danger" id="error_message"></p>
{{ html()->form('PUT' ,route('backend.seasons.update', $data->id))
->attribute('enctype', 'multipart/form-data')
->attribute('data-toggle', 'validator')
->attribute('id', 'form-submit')  // Add the id attribute here
->attribute('novalidate', 'novalidate')  // Disable default browser validation
->class('requires-validation')  // Add the requires-validation class
->open()
}}


        @csrf

        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6>{{ __('movie.lbl_season_title') }} </h6>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row gy-3">
                    <input type="hidden" name="tmdb_id" id="tmdb_id" value="{{ $tmdb_id }}">
                    <div class="col-md-6 col-lg-4">
                        {{ html()->label(__('movie.lbl_poster'), 'poster')->class('form-label') }}
                        <div class="position-relative">
                            <div class="input-group btn-file-upload">
                                {{ html()->button(__('<i class="ph ph-image"></i>'. __('messages.lbl_choose_image')))
                                    ->class('input-group-text form-control')
                                    ->type('button')
                                    ->attribute('data-bs-toggle', 'modal')
                                    ->attribute('data-bs-target', '#exampleModal')
                                    ->attribute('data-image-container', 'selectedImageContainer2')
                                    ->attribute('data-hidden-input', 'file_url2')
                                    ->style('height:7.5rem')
                                }}

                                {{ html()->text('image_input2')
                                    ->class('form-control')
                                    ->placeholder((__('placeholder.lbl_image')))
                                    ->attribute('aria-label', 'Image Input 2')
                                    ->attribute('data-bs-toggle', 'modal')
                                    ->attribute('data-bs-target', '#exampleModal')
                                    ->attribute('data-image-container', 'selectedImageContainer2')
                                    ->attribute('data-hidden-input', 'file_url2')
                                    ->attribute('aria-describedby', 'basic-addon1')
                                }}
                            </div>

                            <div class="mb-3 uploaded-image" id="selectedImageContainer2">
                                @if ($data->poster_url)
                                {{-- <img src="{{ $data->poster_url }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;"> --}}

                                <img id="selectedPosterImage"
                                src="{{ old('poster_url', isset($data) ? $data->poster_url : '') }}" alt="feature-image"
                                class="img-fluid mb-2 avatar-80 "/>

                                    <span class="remove-media-icon"
                                            style="cursor: pointer; font-size: 24px; position: absolute; top: 0; right: 0; color: red;"
                                            onclick="removeImage('file_url2', 'remove_image_flag')">×</span>
                                @else
                                    <p>No image selected.</p>
                                @endif
                            </div>
                            {{ html()->hidden('poster_url')->id('file_url2')->value($data->poster_url) }}
                            {{ html()->hidden('remove_image')->id('remove_image_flag')->value(0) }}
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="mb-3">
                            {{ html()->label(__('movie.lbl_name') . ' <span class="text-danger">*</span>', 'name')->class('form-label') }}
                            {{ html()->text('name')->attribute('value', $data->name)->placeholder(__('placeholder.lbl_season_name'))->class('form-control')->attribute('required','required') }}
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="invalid-feedback" id="name-error">Name field is required</div>
                        </div>
                        <div>
                            {{ html()->label(__('season.lbl_tv_shows') . ' <span class="text-danger">*</span>', 'type')->class('form-label') }}
                            {{ html()->select(
                                    'entertainment_id',
                                    $tvshows->pluck('name', 'id')->prepend(__('placeholder.lbl_select_tvshow'),''), $data->entertainment_id

                                )->class('form-control select2')->id('entertainment_id')->attribute('required','required') }}
                            @error('entertainment_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="invalid-feedback" id="name-error">TV Show field is required</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="mb-3">
                            {{ html()->label(__('movie.lbl_trailer_url_type').' <span class="text-danger">*</span>', 'type')->class('form-label') }}
                            {{ html()->select(
                                    'trailer_url_type',
                                    $upload_url_type->pluck('name', 'value')->prepend(__('placeholder.lbl_select_type'), ''),
                                    old('trailer_url_type', $data->trailer_url_type ?? '') // Set '' as the default value
                                )->class('form-control select2')->id('trailer_url_type') }}
                            @error('trailer_url_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="invalid-feedback" id="name-error">Trailer Type field is required</div>

                        </div>

                        <div class="d-none" id="url_input">
                            {{ html()->label(__('movie.lbl_trailer_url').' <span class="text-danger">*</span>', 'trailer_url')->class('form-label') }}
                            {{ html()->text('trailer_url')->attribute('value', $data->trailer_url)->placeholder(__('placeholder.lbl_trailer_url'))->class('form-control') }}
                            @error('trailer_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="invalid-feedback" id="trailer-url-error">Video URL field is required</div>
                            <div class="invalid-feedback" id="trailer-pattern-error" style="display:none;">
                            Please enter a valid URL starting with http:// or https://.
                        </div>
                        </div>

                        <div class="d-none" id="url_file_input">
                            {{ html()->label(__('movie.lbl_trailer_video').' <span class="text-danger">*</span>', 'trailer_video')->class('form-label') }}

                            <div class="mb-3" id="selectedImageContainer3">
                            @if (Str::endsWith($data->trailer_url, ['.jpeg', '.jpg', '.png', '.gif']))
                                    <img class="img-fluid mb-2" src="{{ $data->trailer_url }}" style="max-width: 100px; max-height: 100px;">
                                @else
                                <video width="400" controls="controls" preload="metadata" >
                                    <source src="{{ $data->trailer_url }}" type="video/mp4" >
                                    </video>
                                @endif
                            </div>

                            <div class="input-group btn-video-link-upload mb-3">
                                {{ html()->button(__('placeholder.lbl_select_file').'<i class="ph ph-upload"></i>')
                                    ->class('input-group-text form-control')
                                    ->type('button')
                                    ->attribute('data-bs-toggle', 'modal')
                                    ->attribute('data-bs-target', '#exampleModal')
                                    ->attribute('data-image-container', 'selectedImageContainer3')
                                    ->attribute('data-hidden-input', 'file_url3')
                                }}

                                {{ html()->text('image_input3')
                                    ->class('form-control')
                                    ->placeholder(__('placeholder.lbl_select_file'))
                                    ->attribute('aria-label', 'Image Input 3')
                                    ->attribute('data-bs-toggle', 'modal')
                                    ->attribute('data-bs-target', '#exampleModal')
                                    ->attribute('data-image-container', 'selectedImageContainer3')
                                    ->attribute('data-hidden-input', 'file_url3')
                                }}
                            </div>
                            {{ html()->hidden('trailer_video')->id('file_url3')->value($data->trailer_url)->attribute('data-validation', 'iq_video_quality') }}

                            @error('trailer_video')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="invalid-feedback" id="trailer-file-error">Video File field is required</div>

                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        {{ html()->label(__('movie.lbl_movie_access') , 'movie_access')->class('form-label') }}
                        <div class="d-flex align-items-center">
                            <label class="form-check form-check-inline form-control px-5 cursor-pointer">
                            <div>
                                <input class="form-check-input" type="radio" name="access" id="paid" value="paid"
                                    onchange="showPlanSelection(this.value === 'paid')"
                                    {{ $data->access == 'paid' ? 'checked' : '' }} checked>
                                <span class="form-check-label" >{{__('movie.lbl_paid')}}</span>
                            </div>
                         </label>
                         <label class="form-check form-check-inline form-control px-5 cursor-pointer">
                            <div >
                                <input class="form-check-input" type="radio" name="access" id="free" value="free"
                                    onchange="showPlanSelection(this.value === 'paid')"
                                    {{ $data->access == 'free' ? 'checked' : '' }}>
                                <span class="form-check-label" >{{__('movie.lbl_free')}}</span>
                            </div>
                        </div>
                    </lable>
                        @error('access')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 col-lg-4 {{ old('access', 'paid') == 'free' ? 'd-none' : '' }}" id="planSelection">
                        {{ html()->label(__('movie.lbl_select_plan'). '<span class="text-danger"> *</span>', 'type')->class('form-label') }}
                        {{ html()->select('plan_id', $plan->pluck('name', 'id')->prepend(__('placeholder.lbl_select_plan'), ''), $data->plan_id)->class('form-control select2')->id('plan_id')->attribute('required','required') }}
                        @error('plan_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="name-error">Plan field is required</div>
                    </div>


                    <div class="col-md-6 col-lg-4">
                        {{ html()->label(__('plan.lbl_status'), 'status')->class('form-label') }}
                        <div class="d-flex justify-content-between align-items-center form-control">
                            {{ html()->label(__('messages.active'), 'status')->class('form-label text-body mb-0') }}
                            <div class="form-check form-switch">
                                {{ html()->hidden('status', 0) }}
                                {{
                                    html()->checkbox('status', $data->status)
                                        ->class('form-check-input')
                                        ->id('status')
                                        ->value(1)
                                }}
                            </div>
                        </div>
                        @error('status')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            {{ html()->label(__('movie.lbl_short_desc'), 'short_desc')->class('form-label') }}
                            <span class="text-primary cursor-pointer" id="GenrateshortDescription" ><i class="ph ph-info" data-bs-toggle="tooltip" title="{{ __('messages.chatgpt_info') }}"></i> {{ __('messages.lbl_chatgpt') }}</span>
                        </div>
                        {{ html()->textarea('short_desc', $data->short_desc)->class('form-control')->id('short_desc')->placeholder(__('placeholder.lbl_season_short_desc'))->rows('8') }}
                        @error('short_desc')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            {{ html()->label(__('movie.lbl_description'). '<span class="text-danger"> *</span>', 'description')->class('form-label mb-0') }}
                            <span class="text-primary cursor-pointer" id="GenrateDescription" ><i class="ph ph-info" data-bs-toggle="tooltip" title="{{ __('messages.chatgpt_info') }}"></i> {{ __('messages.lbl_chatgpt') }}</span>
                        </div>
                        {{ html()->textarea('description',$data->description)->class('form-control')->id('description')->placeholder(__('placeholder.lbl_movie_description'))->attribute('required','required') }}
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="desc-error">Description field is required</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid d-sm-flex justify-content-sm-end gap-3 mb-5">
            {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right')->id('submit-button') }}
        </div>




        {{ html()->form()->close() }}

    @include('components.media-modal')

@endsection
@push('after-scripts')
    <script>

tinymce.init({
    selector: '#description',
    plugins: 'link image code',
    toolbar: 'undo redo | styleselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | removeformat | code | image',
    setup: function(editor) {
                // Setup TinyMCE to listen for changes
                editor.on('change', function(e) {
                    // Get the editor content
                    const content = editor.getContent().trim();
                    const $textarea = $('#description');
                    const $error = $('#desc-error');

                    // Check if content is empty
                    if (content === '') {
                        $textarea.addClass('is-invalid'); // Add invalid class if empty
                        $error.show(); // Show validation message

                    } else {
                        $textarea.removeClass('is-invalid'); // Remove invalid class if not empty
                        $error.hide(); // Hide validation message
                    }
                });
            }
});

$(document).on('click', '.variable_button', function() {
    const textarea = $(document).find('.tab-pane.active');
    const textareaID = textarea.find('textarea').attr('id');
    tinyMCE.activeEditor.selection.setContent($(this).attr('data-value'));
});

        document.addEventListener('DOMContentLoaded', function() {

            function handleTrailerUrlTypeChange(selectedValue) {
                var FileInput = document.getElementById('url_file_input');
                var URLInput = document.getElementById('url_input');
                var trailerfile = document.querySelector('input[name="trailer_video"]');
                var trailerfileError = document.getElementById('trailer-file-error');
                var urlError = document.getElementById('trailer-url-error');
                var URLInputField = document.querySelector('input[name="trailer_url"]');

                if (selectedValue === 'Local') {
                    FileInput.classList.remove('d-none');
                    URLInput.classList.add('d-none');
                    trailerfile.setAttribute('required', 'required');
                    trailerfileError.style.display = 'block';
                    URLInputField.removeAttribute('required');
                } else if (selectedValue === 'URL' || selectedValue === 'YouTube' || selectedValue === 'HLS' ||
                    selectedValue === 'Vimeo') {
                    URLInput.classList.remove('d-none');
                    FileInput.classList.add('d-none');
                    URLInputField.setAttribute('required', 'required');
                    trailerfile.removeAttribute('required');
                    validateTrailerUrlInput()
                } else {
                    FileInput.classList.add('d-none');
                    URLInput.classList.add('d-none');
                    URLInputField.removeAttribute('required');
                    trailerfile.removeAttribute('required');

                }
            }

            function validateTrailerUrlInput() {
                    var URLInput = document.querySelector('input[name="trailer_url"]');
                    var urlPatternError = document.getElementById('trailer-pattern-error');
                    selectedValue = document.getElementById('trailer_url_type').value;
                    if (selectedValue === 'YouTube') {
                        urlPattern = /^(https?:\/\/)?(www\.youtube\.com|youtu\.?be)\/.+$/;
                        urlPatternError.innerText = '';
                        urlPatternError.innerText='Please enter a valid Youtube URL'
                    } else if (selectedValue === 'Vimeo') {
                        urlPattern = /^(https?:\/\/)?(www\.vimeo\.com)\/.+$/;
                        urlPatternError.innerText = '';
                        urlPatternError.innerText='Please enter a valid Vimeo URL'
                    } else {
                        // General URL pattern for other types
                        urlPattern = /^https?:\/\/.+$/;
                         urlPatternError.innerText='Please enter a valid URL'
                    }
                        if (!urlPattern.test(URLInput.value)) {
                            urlPatternError.style.display = 'block';
                            return false;
                        } else {
                            urlPatternError.style.display = 'none';
                            return true;
                        }
                    }

            var initialSelectedValue = document.getElementById('trailer_url_type').value;
            handleTrailerUrlTypeChange(initialSelectedValue);
            $('#trailer_url_type').change(function() {
                var selectedValue = $(this).val();
                handleTrailerUrlTypeChange(selectedValue);
            });

            var URLInput = document.querySelector('input[name="trailer_url"]');
                if (URLInput) {
                    URLInput.addEventListener('input', function() {

                        validateTrailerUrlInput();
                    });
                }
        });

        function showPlanSelection(show) {
            var planSelection = document.getElementById('planSelection');
            var planIdSelect = document.getElementById('plan_id');
            if (show) {
                planSelection.classList.remove('d-none');
                planIdSelect.setAttribute('required', 'required');
            } else {
                planSelection.classList.add('d-none');
                planIdSelect.removeAttribute('required');
                planIdSelect.value = "";

            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            var movieAccessPaid = document.getElementById('paid');
            var movieAccessFree = document.getElementById('free');

            if (movieAccessPaid.checked) {
                showPlanSelection(true);
            } else if (movieAccessFree.checked) {
                showPlanSelection(false);
            }
        });

        $(document).ready(function() {

$('#GenrateshortDescription').on('click', function(e) {

    e.preventDefault();

    var description = $('#short_desc').val();
    var name = $('#name').val();
    var tvshow = $('#entertainment_id').val();

    var generate_discription = "{{ route('backend.seasons.generate-description') }}";
        generate_discription = generate_discription.replace('amp;', '');

    if (!description && !name) {
         return;
     }

     $('#short_desc').text('Loading...')


  $.ajax({

       url: generate_discription,
       type: 'POST',
       headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
       data: {
               description: description,
               name: name,
               tvshow: tvshow,
             },
       success: function(response) {

           $('#short_desc').text('')

            if(response.success){

             var data = response.data;
             $('#short_desc').html(data)

            } else {
                $('#error_message').text(response.message || 'Failed to get Description.');
            }
        },
       error: function(xhr) {
         $('#error_message').text('Failed to get Description.');
         $('#short_desc').text('');
           if (xhr.responseJSON && xhr.responseJSON.message) {
               $('#error_message').text(xhr.responseJSON.message);
           } else {
               $('#error_message').text('An error occurred while fetching the movie details.');
           }
        }
    });
  });
});

$(document).ready(function() {

$('#GenrateDescription').on('click', function(e) {

    e.preventDefault();

    var description = $('#description').val();
    var name = $('#name').val();
    var tvshow = $('#entertainment_id').val();

    var generate_discription = "{{ route('backend.seasons.generate-description') }}";
        generate_discription = generate_discription.replace('amp;', '');

    if (!description && !name) {
         return;
     }

    tinymce.get('description').setContent('Loading...');

  $.ajax({

       url: generate_discription,
       type: 'POST',
       headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
       data: {
               description: description,
               name: name,
               tvshow: tvshow,
             },
       success: function(response) {

          tinymce.get('description').setContent('');

            if(response.success){

             var data = response.data;

             tinymce.get('description').setContent(data);

            } else {
                $('#error_message').text(response.message || 'Failed to get Description.');
            }
        },
       error: function(xhr) {
         $('#error_message').text('Failed to get Description.');
         tinymce.get('description').setContent('');

           if (xhr.responseJSON && xhr.responseJSON.message) {
               $('#error_message').text(xhr.responseJSON.message);
           } else {
               $('#error_message').text('An error occurred while fetching the movie details.');
           }
        }
    });
 });
});

function removeImage(hiddenInputId, removedFlagId) {
    var container = document.getElementById('selectedImageContainer2');
    var hiddenInput = document.getElementById(hiddenInputId);
    var removedFlag = document.getElementById(removedFlagId);

    container.innerHTML = '';
    hiddenInput.value = '';
    removedFlag.value = 1;
}



    </script>

    <style>
        .position-relative {
            position: relative;
        }

        .position-absolute {
            position: absolute;
        }

        .close-icon {
            top: -13px;
            left: 54px;
            background: rgba(255, 0, 0, 0.6);
            border: none;
            border-radius: 50%;
            color: white;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            line-height: 25px;
        }
    </style>
@endpush

