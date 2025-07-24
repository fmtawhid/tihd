@extends('backend.layouts.app')

@section('content')
<x-back-button-component route="backend.videos.index" />
<p class="text-danger" id="error_message"></p>

    {{-- <form method="POST" id="form" action="{{ route('backend.videos.store') }}" enctype="multipart/form-data"> --}}
    {{ html()->form('POST' ,route('backend.videos.store'))
    ->attribute('enctype', 'multipart/form-data')
    ->attribute('data-toggle', 'validator')
    ->attribute('id', 'form-submit')  // Add the id attribute here
    ->class('requires-validation')  // Add the requires-validation class
    ->attribute('novalidate', 'novalidate')  // Disable default browser validation
    ->open()
}}
        @csrf
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5>{{__('customer.about')}} {{ __('video.singular_title') }}</h5>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-lg-4">
                        <div class="position-relative">
                            {{ html()->label(__('movie.lbl_poster'), 'poster')->class('form-label') }}
                            <div class="input-group btn-file-upload">
                                {{ html()->button(__('<i class="ph ph-image"></i>'.__('messages.lbl_choose_image')))
                                    ->class('input-group-text form-control')
                                    ->type('button')
                                    ->attribute('data-bs-toggle', 'modal')
                                    ->attribute('data-bs-target', '#exampleModal')
                                    ->attribute('data-image-container', 'selectedImageContainerPoster')
                                    ->attribute('data-hidden-input', 'file_url_poster')
                                    ->style('height: 13.8rem')
                                }}

                                {{ html()->text('poster_input')
                                    ->class('form-control')
                                    ->placeholder(__('placeholder.lbl_image'))
                                    ->attribute('aria-label', 'Poster Image')
                                    ->attribute('data-bs-toggle', 'modal')
                                    ->attribute('data-bs-target', '#exampleModal')
                                    ->attribute('data-image-container', 'selectedImageContainerPoster')
                                    ->attribute('data-hidden-input', 'file_url_poster')
                                }}
                            </div>
                            <div class="uploaded-image" id="selectedImageContainerPoster">
                                @if(old('poster_url', isset($data) ? $data->poster_url : ''))
                                    <img src="{{ old('poster_url', isset($data) ? $data->poster_url : '') }}" id="selectedPosterImage" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                                @endif
                                <button class="btn btn-danger btn-sm position-absolute close-icon d-none"
                                id="removePostBtn">&times;</button>
                                {{ html()->hidden('poster_url_removed', 0)->id('poster_url_removed') }}
                            </div>
                            {{ html()->hidden('poster_url')->id('file_url_poster')->value(old('poster_url', isset($data) ? $data->poster_url : '')) }}
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="row gy-3">
                            <div class="col-md-6">
                                {{ html()->label(__('video.lbl_title') . ' <span class="text-danger">*</span>', 'name')->class('form-label') }}
                                {{ html()->text('name')->attribute('value', old('name'))->placeholder(__('placeholder.lbl_video_title'))->class('form-control')->attribute('required','required') }}
                                <span class="text-danger" id="error_msg"></span>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="invalid-feedback" id="name-error">Title field is required</div>
                            </div>
                            <div class="col-md-6">
                                {{ html()->label(__('movie.lbl_movie_access') , 'movie_access')->class('form-label') }}
                                <div class="d-flex align-items-center gap-3">
                                <label class="form-check form-check-inline form-control px-5 cursor-pointer">
                                    <div class="">
                                        <input class="form-check-input" type="radio" name="access" id="paid" value="paid"
                                            onchange="showPlanSelection(this.value === 'paid')"
                                            {{ old('access') == 'paid' ? 'checked' : '' }} checked>
                                        <span class="form-check-label" >{{__('movie.lbl_paid')}}</span>
                                    </div>
                                </label>
                                <label class="form-check form-check-inline form-control px-5 cursor-pointer">
                                    <div >
                                        <input class="form-check-input" type="radio" name="access" id="free" value="free"
                                            onchange="showPlanSelection(this.value === 'paid')"
                                            {{ old('access') == 'free' ? 'checked' : '' }}>
                                        <span class="form-check-label" >{{__('movie.lbl_free')}}</span>
                                    </div>
                                </label>
                                </div>
                                @error('access')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 {{ old('access', 'paid') == 'free' ? 'd-none' : '' }}" id="planSelection">
                                {{ html()->label(__('movie.lbl_select_plan'). ' <span class="text-danger">*</span>', 'type')->class('form-label') }}
                                {{ html()->select('plan_id', $plan->pluck('name', 'id')->prepend(__('placeholder.lbl_select_plan'), ''), old('plan_id'))->class('form-control select2')->id('plan_id')}}
                                @error('plan_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="invalid-feedback" id="name-error">Plan field is required</div>
                            </div>
                            {{-- <div class="col-md-6">
                                {{ html()->label(__('movie.lbl_trailer_url_type'), 'type')->class('form-label') }}
                                {{ html()->select(
                                        'trailer_url_type',
                                        $upload_url_type->pluck('name', 'value')->prepend(__('placeholder.lbl_select_type'), ''),
                                        old('trailer_url_type', ''), // Set '' as the default value
                                    )->class('form-control select2')->id('trailer_url_type') }}
                                @error('trailer_url_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> --}}

                            {{-- <div class="col-md-6 d-none" id="url_input">
                                {{ html()->label(__('movie.lbl_trailer_url'), 'trailer_url')->class('form-label') }}
                                {{ html()->text('trailer_url')->attribute('value', old('trailer_url'))->placeholder(__('placeholder.lbl_trailer_url'))->class('form-control') }}
                                @error('trailer_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 d-none" id="url_file_input">

                                    {{ html()->label(__('movie.lbl_trailer_video'), 'trailer_video')->class('form-label') }}

                                    <div class="input-group btn-video-link-upload">
                                        {{ html()->button(__('placeholder.lbl_select_file').'<i class="ph ph-upload"></i>')->class('input-group-text form-control')->type('button')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#exampleModal')->attribute('data-image-container', 'selectedImageContainertailerurl')->attribute('data-hidden-input', 'file_url_trailer') }}

                                        {{ html()->text('trailer_input')->class('form-control')->placeholder('Select Image')->attribute('aria-label', 'Trailer Image')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#exampleModal')->attribute('data-image-container', 'selectedImageContainertailerurl')->attribute('data-hidden-input', 'file_url_trailer') }}
                                    </div>

                                    <div class="mt-3" id="selectedImageContainertailerurl">
                                        @if (old('trailer_url', isset($data) ? $data->trailer_url : ''))
                                            <img src="{{ old('trailer_url', isset($data) ? $data->trailer_url : '') }}"
                                                class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                                        @endif
                                    </div>

                                    {{ html()->hidden('trailer_video')->id('file_url_trailer')->value(old('trailer_url', isset($data) ? $data->poster_url : ''))->attribute('data-validation', 'iq_video_quality')  }}

                                    @error('trailer_video')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                            </div> --}}
                            <div class="col-md-6">
                                {{ html()->label(__('plan.lbl_status'), 'status')->class('form-label') }}
                                <div class="d-flex justify-content-between align-items-center form-control">
                                    {{ html()->label(__('messages.active'), 'status')->class('form-label mb-0 text-body') }}
                                    <div class="form-check form-switch">
                                        {{ html()->hidden('status', 0) }}
                                        {{
                                            html()->checkbox('status', old('status', 1))
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
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            {{ html()->label(__('movie.lbl_short_desc'), 'short_desc')->class('form-label') }}
                            <span class="text-primary cursor-pointer" id="GenrateshortDescription" ><i class="ph ph-info" data-bs-toggle="tooltip" title="{{ __('messages.chatgpt_info') }}"></i> {{ __('messages.lbl_chatgpt') }}</span>
                        </div>

                        {{ html()->textarea('short_desc', old('short_desc'))->class('form-control')->id('short_desc')->placeholder(__('placeholder.episode_short_desc'))->rows('8') }}
                        @error('short_desc')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            {{ html()->label(__('movie.lbl_description'). '<span class="text-danger"> *</span>', 'description')->class('form-label mb-0') }}
                            <span class="text-primary cursor-pointer" id="GenrateDescription" ><i class="ph ph-info" data-bs-toggle="tooltip" title="{{ __('messages.chatgpt_info') }}"></i> {{ __('messages.lbl_chatgpt') }}</span>
                        </div>
                        {{ html()->textarea('description', old('description'))->class('form-control')->id('description')->placeholder(__('placeholder.lbl_video_description'))->attribute('required','required') }}
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="desc-error">Description field is required</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-5 pt-5 mb-3">
            <h5>{{ __('movie.lbl_basic_info') }}</h5>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-6 col-lg-4">
                        {{ html()->label(__('movie.lbl_duration') . ' <span class="text-danger">*</span>', 'duration')->class('form-label') }}
                        {{ html()->time('duration')->attribute('value', old('duration'))->placeholder(__('movie.lbl_duration'))->class('form-control min-datetimepicker-time')->attribute('required','required') }}
                        @error('duration')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="duration-error">Duration field is required</div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        {{ html()->label(__('movie.lbl_release_date') , 'release_date')->class('form-label') }}
                        {{ html()->date('release_date')->attribute('value', old('release_date'))->placeholder(__('movie.lbl_release_date'))->class('form-control datetimepicker') }}
                        @error('release_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        {{-- <div class="invalid-feedback" id="release_date-error">Release Date field is required</div> --}}
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
                    <div class="col-md-6 col-lg-4">
                        {{ html()->label(__('movie.lbl_download_status'), 'download_status')->class('form-label') }}
                        <div class="d-flex justify-content-between align-items-center form-control">
                            {{ html()->label(__('movie.lbl_download_status'), 'download_status')->class('form-label mb-0 text-body') }}
                            <div class="form-check form-switch">
                                {{ html()->hidden('download_status', 0) }}
                                {{ html()->checkbox('download_status',  old('download_status', 1))->class('form-check-input')->id('download_status')->value(1) }}
                            </div>
                        </div>
                        @error('download_status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-5 pt-5 mb-3">
            <h5>{{ __('movie.lbl_video_info') }}</h5>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-6">
                        {{ html()->label(__('movie.lbl_video_upload_type'). '<span class="text-danger">*</span>', 'video_upload_type')->class('form-label') }}
                        {{ html()->select(
                                'video_upload_type',
                                $upload_url_type->pluck('name', 'value')->prepend(__('placeholder.lbl_select_video_type'), ''),
                                old('video_upload_type', ''),
                            )->class('form-control select2')->id('video_upload_type')->required() }}
                        @error('video_upload_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="name-error">Video Type field is required</div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 d-none" id="video_url_input_section">
                            {{ html()->label(__('movie.video_url_input') . '<span class="text-danger">*</span>', 'video_url_input')->class('form-control-label') }}
                            {{ html()->text('video_url_input')->attribute('value', old('video_url_input'))->placeholder(__('placeholder.video_url_input'))->class('form-control') }}
                            @error('video_url_input')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="invalid-feedback" id="url-error">Video URL field is required</div>
                            <div class="invalid-feedback" id="url-pattern-error" style="display:none;">
                            Please enter a valid URL starting with http:// or https://.
                        </div>
                        </div>

                        <div class="mb-3 d-none" id="video_file_input_section">
                            {{ html()->label(__('movie.video_file_input') . '<span class="text-danger">*</span>', 'video_file')->class('form-label') }}

                            <div class="input-group btn-video-link-upload mb-3">
                                {{ html()->button(__('placeholder.lbl_select_file').'<i class="ph ph-upload"></i>')->class('input-group-text form-control')->type('button')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#exampleModal')->attribute('data-image-container', 'selectedImageContainerVideourl')->attribute('data-hidden-input', 'file_url_video') }}

                                {{ html()->text('video_file_input')->class('form-control')->placeholder('Select Image')->attribute('aria-label', 'Video Image')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#exampleModal')->attribute('data-image-container', 'selectedImageContainerVideourl')->attribute('data-hidden-input', 'file_url_video') }}
                            </div>

                            <div class="mt-3" id="selectedImageContainerVideourl">
                                @if (old('video_file_input'))
                                    <img src="{{ old('video_file_input') }}"
                                        class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                                @endif
                            </div>

                            {{ html()->hidden('video_file_input')->id('file_url_video')->value(old('video_file_input'))->attribute('data-validation', 'iq_video_quality')  }}

                            @error('video_file_input')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="invalid-feedback" id="file-error">Video File field is required</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="d-flex align-items-center justify-content-between mt-5 pt-5 mb-3">
            <h5>{{ __('movie.lbl_quality_info') }}</h5>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center justify-content-between form-control">
                            <label for="enable_quality" class="form-label mb-0 text-body">{{ __('movie.lbl_enable_quality') }}</label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="enable_quality" value="0">
                                <input type="checkbox" name="enable_quality" id="enable_quality"
                                    class="form-check-input" value="1"
                                    {{ old('enable_quality', false) ? 'checked' : '' }} onchange="toggleQualitySection()">
                            </div>
                        </div>
                        @error('enable_quality')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div id="enable_quality_section" class="col-md-12 enable_quality_section d-none">
                        <div id="video-inputs-container-parent">
                            <div class="row gy-3 video-inputs-container">
                                <div class="col-md-4">
                                    {{ html()->label(__('movie.lbl_video_upload_type'), 'video_quality_type')->class('form-label') }}
                                    {{ html()->select(
                                            'video_quality_type[]',
                                            $upload_url_type->pluck('name', 'value')->prepend(__('placeholder.lbl_select_video_type'), ''),
                                            old('video_quality_type', ''),
                                        )->class('form-control select2 video_quality_type') }}
                                    @error('video_quality_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 video-input">
                                    {{ html()->label(__('movie.lbl_video_quality'), 'video_quality')->class('form-label') }}
                                    {{ html()->select('video_quality[]', $video_quality->pluck('name', 'value')->prepend(__('placeholder.lbl_select_quality'), ''))->class('form-control select2 video_quality') }}
                                </div>
                                <div class="col-md-4 d-none video-url-input quality_video_input">
                                    {{ html()->label(__('movie.video_url_input'), 'quality_video_url_input')->class('form-label') }}
                                    {{ html()->text('quality_video_url_input[]')->placeholder(__('placeholder.video_url_input'))->class('form-control') }}
                                </div>
                                <div class="col-md-4 d-none video-file-input quality_video_file_input">
                                    {{ html()->label(__('movie.video_file_input'), 'quality_video')->class('form-label') }}
                                    <div class="input-group btn-video-link-upload">
                                        {{ html()->button(__('placeholder.lbl_select_file').'<i class="ph ph-upload"></i>')->class('input-group-text form-control')->type('button')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#exampleModal')->attribute('data-image-container', 'selectedImageContainerVideoqualityurl')->attribute('data-hidden-input', 'file_url_videoquality') }}
                                        {{ html()->text('videoquality_input')->class('form-control')->placeholder('Select Image')->attribute('aria-label', 'Video Quality Image')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#exampleModal')->attribute('data-image-container', 'selectedImageContainerVideoqualityurl')->attribute('data-hidden-input', 'file_url_videoquality') }}
                                    </div>
                                    <div class="mt-3" id="selectedImageContainerVideoqualityurl">
                                        @if (old('video_quality_url', isset($data) ? $data->video_quality_url : ''))
                                            <img src="{{ old('video_quality_url', isset($data) ? $data->video_quality_url : '') }}"
                                                class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                                        @endif
                                    </div>
                                    {{ html()->hidden('quality_video[]')->id('file_url_videoquality')->value(old('video_quality_url', isset($data) ? $data->poster_url : ''))->attribute('data-validation', 'iq_video_quality') }}
                                    @error('quality_video')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-sm-12 text-end mb-3">
                                    <button type="button"class="btn btn-secondary-subtle btn-sm fs-4 remove-video-input d-none"><i class="ph ph-trash align-middle"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <a id="add_more_video" class="btn btn-sm btn-primary"><i class="ph ph-plus-circle"></i> {{__('episode.lbl_add_more')}}</a>
                        </div>
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

                if (selectedValue === 'Local') {
                    FileInput.classList.remove('d-none');
                    URLInput.classList.add('d-none');
                } else if (selectedValue === 'URL' || selectedValue === 'YouTube' || selectedValue === 'HLS' ||
                    selectedValue === 'Vimeo') {
                    URLInput.classList.remove('d-none');
                    FileInput.classList.add('d-none');
                } else {
                    FileInput.classList.add('d-none');
                    URLInput.classList.add('d-none');
                }
            }

            var initialSelectedValue = document.getElementById('trailer_url_type').value;
            handleTrailerUrlTypeChange(initialSelectedValue);
            $('#trailer_url_type').change(function() {
                var selectedValue = $(this).val();
                handleTrailerUrlTypeChange(selectedValue);
            });
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

        function toggleQualitySection() {

var enableQualityCheckbox = document.getElementById('enable_quality');
var enableQualitySection = document.getElementById('enable_quality_section');

if (enableQualityCheckbox.checked) {

 enableQualitySection.classList.remove('d-none');

  } else {

  enableQualitySection.classList.add('d-none');
}
}

document.addEventListener('DOMContentLoaded', function () {
toggleQualitySection();
});


document.addEventListener('DOMContentLoaded', function() {

 function handleVideoUrlTypeChange(selectedtypeValue) {
     var VideoFileInput = document.getElementById('video_file_input_section');
     var VideoURLInput = document.getElementById('video_url_input_section');
     var videourl = document.getElementById('video_url_input');
    var videofile = document.querySelector('input[name="video_file_input"]');
    var fileError = document.getElementById('file-error');
    var urlError = document.getElementById('url-error');
    var urlPatternError = document.getElementById('url-pattern-error');
     if (selectedtypeValue === 'Local') {
         VideoFileInput.classList.remove('d-none');
         VideoURLInput.classList.add('d-none');
         videourl.removeAttribute('required');
        videofile.setAttribute('required', 'required');
        fileError.style.display = 'block';
     } else if (selectedtypeValue === 'URL' || selectedtypeValue === 'YouTube' || selectedtypeValue ===
         'HLS' || selectedtypeValue === 'Vimeo') {
         VideoURLInput.classList.remove('d-none');
         VideoFileInput.classList.add('d-none');
         videourl.setAttribute('required', 'required');
        videofile.removeAttribute('required');
        validateVideoUrlInput();
     } else {
         VideoFileInput.classList.add('d-none');
         VideoURLInput.classList.add('d-none');

     }
 }


 function validateVideoUrlInput() {
    var videourl = document.querySelector('input[name="video_url_input"]');
    var urlError = document.getElementById('url-error');
    var urlPatternError = document.getElementById('url-pattern-error');

    if (videourl.value === '') {
        urlError.style.display = 'block';
        urlPatternError.style.display = 'none';
        return false;
    } else {
        urlError.style.display = 'none';
        selectedValue = document.getElementById('video_upload_type').value;
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
                        urlPatternError.innerText='Please enter a valid URL starting with http:// or https://.'
                    } // Simple URL pattern validation
        if (!urlPattern.test(videourl.value)) {
            urlPatternError.style.display = 'block';
            return false;
        } else {
            urlPatternError.style.display = 'none';
            return true;
        }
    }
}

var initialSelectedValue = document.getElementById('video_upload_type').value;
handleVideoUrlTypeChange(initialSelectedValue);
$('#video_upload_type').change(function() {
    var selectedtypeValue = $(this).val();
    handleVideoUrlTypeChange(selectedtypeValue);
});

// Real-time validation while typing
var videourl = document.querySelector('input[name="video_url_input"]');
if (videourl) {
    videourl.addEventListener('input', function() {
        validateVideoUrlInput();
    });
}
});



$(document).ready(function() {

$('#GenrateshortDescription').on('click', function(e) {

    e.preventDefault();

    var description = $('#short_desc').val();
    var name = $('#name').val();
    var type='short_desc';

    var generate_discription = "{{ route('backend.videos.generate-description') }}";
        generate_discription = generate_discription.replace('amp;', '');

    if (!description && !name) {
        // $('#error_msg').text('Name field is required');
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
               type:type
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
    var type='short_desc';


    var generate_discription = "{{ route('backend.seasons.generate-description') }}";
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
               tvshow: tvshow,
               type:type
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
