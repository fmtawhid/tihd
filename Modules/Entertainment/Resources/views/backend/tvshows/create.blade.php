@extends('backend.layouts.app')
@section('content')
<x-back-button-component route="backend.tvshows.index" />
<p class="text-danger" id="error_message"></p>
@if(isenablemodule('enable_tmdb_api')==1)
<div class="d-flex align-items-end justify-content-between gap-3 mb-3">

    <div class="flex-grow-1">
        <a class="ph ph-info" data-bs-toggle="tooltip" title="To get a tvshow id, click on icon ." href="https://www.themoviedb.org/tv/63174-lucifer" target="_blank"></a>
        {{ html()->label(__('movie.import_tvshow') , 'tvshow_id')->class('form-label') }}
        {{ html()->text('tvshow_id')->attribute('value', old('tvshow_id'))->placeholder(__('placeholder.lbl_tvshow_id'))->class('form-control') }}
        <span class="text-danger" id="tvshow_id_error"></span>
    </div>
    <div id="loader" style="display: none;">
        <button class="btn btn-md btn-primary float-right">{{__('tvshow.lbl_loading')}}</button>
    </div>
    <button class="btn btn-md btn-primary float-right" id="import_tvshow_id">{{__('tvshow.lbl_import')}}</button>
</div>
@endif

{{ html()->form('POST' ,route('backend.entertainments.store'))
    ->attribute('enctype', 'multipart/form-data')
    ->attribute('data-toggle', 'validator')
    ->attribute('id', 'form-submit')  // Add the id attribute here
    ->class('requires-validation')  // Add the requires-validation class
    ->attribute('novalidate', 'novalidate')  // Disable default browser validation
    ->open()
}}
        @csrf
        <div class="d-flex align-items-center justify-content-between mt-5 pt-4 mb-3">
            <h6>{{__('customer.about')}} {{__('season.lbl_tv_shows')}}</h6>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-6 col-lg-3 position-relative">
                        {{ html()->hidden('type', $type)->id('type') }}
                        {{ html()->hidden('tmdb_id', null)->id('tmdb_id') }}
                        <div class="position-relative">
                            {{ html()->label(__('movie.lbl_thumbnail'), 'thumbnail')->class('form-label') }}
                            <div class="input-group btn-file-upload">
                                {{ html()->button('<i class="ph ph-image"></i> '. __('messages.lbl_choose_image'))
                                    ->class('input-group-text form-control')
                                    ->type('button')
                                    ->attribute('data-bs-toggle', 'modal')
                                    ->attribute('data-bs-target', '#exampleModal')
                                    ->attribute('data-image-container', 'selectedImageContainerThumbnail')
                                    ->attribute('data-hidden-input', 'file_url_thumbnail')
                                    ->style('height:13.5rem')
                                }}

                                {{ html()->text('thumbnail_input')
                                    ->class('form-control')
                                    ->placeholder('Select Image')
                                    ->attribute('aria-label', 'Thumbnail Image')
                                    ->attribute('data-bs-toggle', 'modal')
                                    ->attribute('data-bs-target', '#exampleModal')
                                    ->attribute('data-image-container', 'selectedImageContainerThumbnail')
                                    ->attribute('data-hidden-input', 'file_url_thumbnail')
                                }}
                            </div>

                            <div class="uploaded-image" id="selectedImageContainerThumbnail">
                                    <img id="selectedImage"
                                        src="{{ old('thumbnail_url', isset($data) ? $data->thumbnail_url : '') }}"
                                        alt="feature-image" class="img-fluid mb-2"
                                        style="{{ old('thumbnail_url', isset($data) ? $data->thumbnail_url : '') ? '' : 'display:none;' }}"/>
                            </div>
                            {{ html()->hidden('thumbnail_url')->id('file_url_thumbnail')->value(old('thumbnail_url', isset($data) ? $data->thumbnail_url : '')) }}
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="position-relative">
                            {{ html()->label(__('movie.lbl_poster'), 'poster')->class('form-label form-control-label') }}
                            <div class="input-group btn-file-upload mb-3">
                                {{ html()->button('<i class="ph ph-image"></i>'. __('messages.lbl_choose_image'))
                                    ->class('input-group-text form-control')
                                    ->type('button')
                                    ->attribute('data-bs-toggle', 'modal')
                                    ->attribute('data-bs-target', '#exampleModal')
                                    ->attribute('data-image-container', 'selectedImageContainerPoster')
                                    ->attribute('data-hidden-input', 'file_url_poster')
                                    ->style('height:13.5rem')
                                }}

                                {{ html()->text('poster_input')
                                    ->class('form-control')
                                    ->placeholder('Select Image')
                                    ->attribute('aria-label', 'Poster Image')
                                    ->attribute('data-bs-toggle', 'modal')
                                    ->attribute('data-bs-target', '#exampleModal')
                                    ->attribute('data-image-container', 'selectedImageContainerPoster')
                                    ->attribute('data-hidden-input', 'file_url_poster')
                                }}
                            </div>


                            <div class="uploaded-image" id="selectedImageContainerPoster">
                                <img id="selectedPosterImage"
                                    src="{{ old('poster_url', isset($data) ? $data->poster_url : '') }}" alt="feature-image"
                                    class="img-fluid mb-2 avatar-80 "
                                    style="{{ old('poster_url', isset($data) ? $data->poster_url : '') ? '' : 'display:none;' }}" />
                            </div>
                            {{ html()->hidden('poster_url')->id('file_url_poster')->value(old('poster_url', isset($data) ? $data->poster_url : '')) }}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                       <div class="mb-3">
                        {{ html()->label(__('movie.lbl_name') . ' <span class="text-danger">*</span>', 'name')->class('form-label form-control-label') }}
                            {{ html()->text('name')->attribute('value', old('name'))->placeholder(__('placeholder.lbl_movie_name'))->class('form-control')->attribute('required','required') }}
                            <span class="text-danger" id="error_msg"></span>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="invalid-feedback" id="name-error">Name field is required</div>
                       </div>
                       <div>
                       <div class="mb-3">
                            {{ html()->label(__('movie.lbl_trailer_url_type'). ' <span class="text-danger">*</span>', 'type')->class('form-label') }}
                            {{ html()->select(
                                    'trailer_url_type',
                                    $upload_url_type->pluck('name', 'value')->prepend(__('placeholder.lbl_select_type'), ''),
                                    old('trailer_url_type', ''), // Set '' as the default value
                                )->class('form-control select2')->id('trailer_url_type') }}
                            @error('trailer_url_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="invalid-feedback" id="name-error">Trailer Type field is required</div>
                        </div>
                        <div class="d-none" id="url_input">
                            {{ html()->label(__('movie.lbl_trailer_url'). ' <span class="text-danger">*</span>', 'trailer_url')->class('form-label form-control-label') }}
                            {{ html()->text('trailer_url')->attribute('value', old('trailer_url'))->placeholder(__('placeholder.lbl_trailer_url'))->class('form-control') }}
                            @error('trailer_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="invalid-feedback" id="trailer-url-error">Video URL field is required</div>
                                    <div class="invalid-feedback" id="trailer-pattern-error" style="display:none;">
                                    Please enter a valid URL starting with http:// or https://.
                                </div>
                        </div>
                        <div class="position-relative d-none" id="url_file_input">
                            {{ html()->label(__('movie.lbl_trailer_video'). ' <span class="text-danger">*</span>', 'trailer_video')->class('form-label form-control-label') }}

                            <div class="input-group btn-video-link-upload">
                                {{ html()->button(__('placeholder.lbl_select_file').'<i class="ph ph-image"></i>')
                                    ->class('input-group-text form-control')
                                    ->type('button')
                                    ->attribute('data-bs-toggle', 'modal')
                                    ->attribute('data-bs-target', '#exampleModal')
                                    ->attribute('data-image-container', 'selectedImageContainertailerurl')
                                    ->attribute('data-hidden-input', 'file_url_trailer')
                                }}

                                {{ html()->text('trailer_input')
                                    ->class('form-control')
                                    ->placeholder('Select Image')
                                    ->attribute('aria-label', 'Trailer Image')
                                    ->attribute('data-bs-toggle', 'modal')
                                    ->attribute('data-bs-target', '#exampleModal')
                                    ->attribute('data-image-container', 'selectedImageContainertailerurl')
                                    ->attribute('data-hidden-input', 'file_url_trailer')
                                }}
                            </div>
                            <div class="mt-3" id="selectedImageContainertailerurl">
                                @if(old('trailer_url', isset($data) ? $data->trailer_url : ''))
                                    <img src="{{ old('trailer_url', isset($data) ? $data->trailer_url : '') }}" class="img-fluid avatar-150">
                                @endif
                            </div>

                            {{ html()->hidden('trailer_video')->id('file_url_trailer')->value(old('trailer_url', isset($data) ? $data->trailer_url : ''))->attribute('data-validation', 'iq_video_quality') }}
                            @error('trailer_video')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="invalid-feedback" id="trailer-file-error">Video File field is required</div>

                        </div>
                       </div>
                    </div>
                    <div class="col-md-12">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            {{ html()->label(__('movie.lbl_description'). '<span class="text-danger"> *</span>', 'description')->class('form-label mb-0') }}
                            <span class="text-primary cursor-pointer" id="GenrateDescription" ><i class="ph ph-info" data-bs-toggle="tooltip" title="{{ __('messages.chatgpt_info') }}"></i> {{ __('messages.lbl_chatgpt') }}</span>
                        </div>
                        {{ html()->textarea('description', old('description'))->class('form-control')->id('description')->placeholder(__('placeholder.lbl_movie_description'))->rows(4)->attribute('required','required') }}
                        <span class="text-danger" id="error_msg"></span>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="desc-error">Description field is required</div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        {{ html()->label(__('movie.lbl_movie_access'), 'movie_access')->class('form-label') }}
                        <div class="d-flex align-items-center gap-3">
                            <label class="form-check form-check-inline form-control px-5 cursor-pointer">
                            <div>
                                <input class="form-check-input" type="radio" name="movie_access" id="paid" value="paid"
                                    onchange="showPlanSelection(this.value === 'paid')"
                                    {{ old('movie_access') == 'paid' ? 'checked' : '' }} checked>
                                <span class="form-check-label" for="paid">{{__('movie.lbl_paid')}}</span>
                            </div>
                        </label>

                        <label class="form-check form-check-inline form-control px-5 cursor-pointer">
                            <div>
                                <input class="form-check-input" type="radio" name="movie_access" id="free" value="free"
                                    onchange="showPlanSelection(this.value === 'paid')"
                                    {{ old('movie_access') == 'free' ? 'checked' : '' }}>
                                <span class="form-check-label" for="free">{{__('movie.lbl_free')}}</span>
                            </div>
                        </div>
                    </label>
                        @error('movie_access')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 col-lg-4 {{ old('movie_access', 'paid') == 'free' ? 'd-none' : '' }}" id="planSelection">
                        {{ html()->label(__('movie.lbl_select_plan'). '<span class="text-danger"> *</span>', 'type')->class('form-label') }}
                        {{ html()->select('plan_id', $plan->pluck('name', 'id')->prepend(__('placeholder.lbl_select_plan'), ''), old('plan_id'))->class('form-control select2')->id('plan_id') }}
                        @error('plan_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="name-error">Plan field is required</div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        {{ html()->label(__('plan.lbl_status'), 'status')->class('form-label') }}
                        <div class="d-flex justify-content-between align-items-center form-control">
                            {{ html()->label(__('messages.active'), 'status')->class('form-label mb-0 text-body') }}
                            <div class="form-check form-switch">
                                {{ html()->hidden('status', 0) }}
                                {{ html()->checkbox('status', old('status', 1))->class('form-check-input')->id('status')->value(1) }}
                            </div>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-5 pt-4 mb-3">
            <h5>{{ __('movie.lbl_basic_info') }}</h5>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-6 col-lg-4">
                        {{ html()->label(__('movie.lbl_movie_language') . '<span class="text-danger">*</span>', 'language')->class('form-label') }}
                        {{ html()->select('language', $movie_language->pluck('name', 'value')->prepend(__('placeholder.lbl_select_language'), ''), old('language'))->class('form-control select2')->id('language')->attribute('required','required') }}
                        @error('language')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="name-error">Language field is required</div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        {{ html()->label(__('movie.lbl_genres') . '<span class="text-danger">*</span>', 'genres')->class('form-label') }}
                        {{ html()->select('genres[]', $genres->pluck('name', 'id')->prepend(__('placeholder.lbl_select_genre'), ''), old('genres'))->class('form-control select2')->id('genres')->multiple()->attribute('required','required') }}
                        @error('genres')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="name-error">Genres field is required</div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        {{ html()->label(__('movie.lbl_countries'), 'countries')->class('form-label') }}
                        {{ html()->select('countries[]', $countries->pluck('name', 'id')->prepend(__('placeholder.lbl_select_country'), ''), old('countries'))->class('form-control select2')->id('countries')->multiple() }}
                        @error('countries')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="countries-error">Countries field is required</div>
                    </div>


                    <div class="col-md-6 col-lg-4">
                        {{ html()->label(__('movie.lbl_imdb_rating') . ' <span class="text-danger">*</span>', 'IMDb_rating')->class('form-label') }}
                        {{ html()->text('IMDb_rating')
                                ->attribute('value', old('IMDb_rating'))
                                ->placeholder(__('movie.lbl_imdb_rating'))
                                ->class('form-control')
                                ->required() }}

                        @error('IMDb_rating')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="imdb-error">IMDB Rating field is required</div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        {{ html()->label(__('movie.lbl_content_rating') . '<span class="text-danger">*</span>', 'content_rating')->class('form-label') }}

                        {{ html()->text('content_rating')->attribute('value', old('content_rating'))->placeholder(__('placeholder.lbl_content_rating'))->class('form-control')->attribute('required','required') }}

                        @error('content_rating')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="name-error">Content Rating field is required</div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        {{ html()->label(__('movie.lbl_duration').'<span class="text-danger">*</span>' , 'duration')->class('form-label') }}
                        {{ html()->time('duration')->attribute('value', old('duration'))->placeholder(__('movie.lbl_duration'))->class('form-control min-datetimepicker-time')->attribute('required','required')->id('duration') }}
                        @error('duration')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="duration-error">Duration field is required</div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        {{ html()->label(__('movie.lbl_release_date').'<span class="text-danger">*</span>', 'release_date')->class('form-label') }}
                        {{ html()->date('release_date')->attribute('value', old('release_date'))->placeholder(__('movie.lbl_release_date'))->class('form-control datetimepicker')->attribute('required','required')->id('release_date') }}
                        @error('release_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="release_date-error">Release Date field is required</div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        {{ html()->label(__('movie.lbl_age_restricted'), 'is_restricted')->class('form-label') }}
                        <div class="d-flex justify-content-between align-items-center form-control">
                            {{ html()->label(__('movie.lbl_child_content'), 'is_restricted')->class('form-label mb-0 text-body') }}
                            <div class="form-check form-switch">
                                {{ html()->hidden('is_restricted', 0) }}
                                {{ html()->checkbox('is_restricted', old('is_restricted', false))->class('form-check-input')->id('is_restricted') }}
                            </div>
                        </div>
                        @error('is_restricted')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-5 pt-4 mb-3">
            <h5>{{ __('movie.lbl_actor_director') }}</h5>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        {{ html()->label(__('movie.lbl_actors') . '<span class="text-danger">*</span>', 'actors')->class('form-label') }}
                        {{ html()->select('actors[]', $actors->pluck('name', 'id'), old('actors'))->class('form-control select2')->id('actors')->multiple()->attribute('required','required') }}
                        @error('actors')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="name-error">Actors field is required</div>
                    </div>
                    <div class="col-md-6">
                        {{ html()->label(__('movie.lbl_directors') . '<span class="text-danger">*</span>', 'directors')->class('form-label') }}
                        {{ html()->select('directors[]', $directors->pluck('name', 'id'), old('directors'))->class('form-control select2')->id('directors')->multiple()->attribute('required','required') }}
                        @error('directors')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="name-error">Directors field is required</div>
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
$(document).ready(function() {
    $('#genres').select2({
        width: '100%',
        placeholder: "{{ __('movie.lbl_genres') }}",  // Set the placeholder text here
        allowClear: true  // Allows clearing the selection
    });

    $('#countries').select2({
    width: '100%',
    placeholder: "{{ __('movie.lbl_countries') }}",  // Set the placeholder text here
    allowClear: true  // Allows clearing the selection
});


    $('#actors').select2({
        width: '100%',
        placeholder: "{{ __('movie.lbl_actors') }}",  // Set the placeholder text here
        allowClear: true  // Allows clearing the selection
    });

    $('#directors').select2({
        width: '100%',
        placeholder: "{{ __('movie.lbl_directors') }}",  // Set the placeholder text here
        allowClear: true  // Allows clearing the selection
    });
});
document.addEventListener('DOMContentLoaded', function () {
        flatpickr('.min-datetimepicker-time', {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i", // Format for time (24-hour format)
            time_24hr: true // Enable 24-hour format

        });

        flatpickr('.datetimepicker', {
            dateFormat: "Y-m-d", // Format for date (e.g., 2024-08-21)

        });
    });


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
                        trailerfile.setAttribute('required', 'required');
                        trailerfileError.style.display = 'block';
                        FileInput.classList.remove('d-none');
                        URLInput.classList.add('d-none');
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

     $('#import_tvshow_id').on('click', function(e) {
        e.preventDefault();

        var tvshowID = $('#tvshow_id').val();
        $('#tvshow_id_error').text('');
        $('#error_message').text('');

        var baseUrl = "{{ env('APP_URL') }}";
        var url = baseUrl + '/app/tvshows/import-tvshow/' + tvshowID;

        if (!tvshowID) {
            $('#tvshow_id_error').text('TV show is required.');
            return;
        }

        $('#loader').show();
        $('#import_tvshow_id').hide();

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {

                $('#loader').hide();
                $('#import_tvshow_id').show();

                if(response.success){

                 var data = response.data;
                 $('#tmdb_id').val(data.id);
                 $('#selectedImage').attr('src', data.thumbnail_url).show();
                 $('#selectedPosterImage').attr('src', data.poster_url).show();
                 $('#name').val(data.name);
                 $('#description').val(data.description);
                 $('#trailer_url_type').val(data.trailer_url_type).trigger('change');
                 $('#trailer_url').val(data.trailer_url);
                 $('#release_date').val(data.release_date);
                 $('#duration').val(data.duration);
                 $('#file_url_thumbnail').val(data.thumbnail_url);
                 $('#file_url_poster').val(data.poster_url);

                 var all_genres = data.all_genres;

                 $('#genres').empty().append('<option value="">Select Genre</option>');
                 $.each(all_genres, function(index, genre) {

                     $('#genres').append('<option value="' + genre.id + '">' + genre.name + '</option>');
                 });
                 $('#genres').val(data.genres).trigger('change');

                 var all_languages = data.all_language;

                 $('#language').empty().append('<option value="">Select Language</option>');
                 $.each(all_languages, function(index, language) {
                     $('#language').append('<option value="' + language.value + '">' + language.name + '</option>');
                 });
                 $('#language').val(data.language.toLowerCase()).trigger('change');


                 var all_actors = data.all_actors;
                 $('#actors').empty().append('<option value="">Select Actors</option>');
                 $.each(all_actors, function(index, actor) {
                     $('#actors').append('<option value="' + actor.id + '">' + actor.name + '</option>');
                 });
                 $('#actors').val(data.actors).trigger('change');


                 var all_directors = data.all_directors;
                 $('#directors').empty().append('<option value="">Select Directors</option>');
                 $.each(all_directors, function(index, director) {
                     $('#directors').append('<option value="' + director.id + '">' + director.name + '</option>');
                 });
                 $('#directors').val(data.directors).trigger('change');


                 if(data.is_restricted) {
                        $('#is_restricted').prop('checked', true).val(1);
                    } else {
                        $('#is_restricted').prop('checked', false).val(0);
                    }

                    if(data.thumbnail_url){

                        $('#selectedImage').attr('src', data.thumbnail_url).show();
                    }

                    if(data.poster_url) {

                        $('#selectedPosterImage').attr('src', data.poster_url).show();
                    }
                    if (data.movie_access === 'paid') {
                      document.getElementById('paid').checked = true;
                      showPlanSelection(true);
                    } else {

                      document.getElementById('free').checked = true;
                      showPlanSelection(false);
                    }

                } else {
                    $('#error_message').text(response.message || 'Failed to import movie details.');
                }
            },
            error: function(xhr) {
                $('#import_tvshow_id').show();
                $('#loader').hide();
                $('#import_movie').show();
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

                   var generate_discription = "{{ route('backend.movies.generate-description') }}";
                       generate_discription = generate_discription.replace('amp;', '');

                   if (!description && !name) {
                    $('#error_msg').text('Name field is required');
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


        </script>

        <style>
            .position-relative {
                position: relative;
            }

            .position-absolute {
                position: absolute;
            }


        </style>
    @endpush
