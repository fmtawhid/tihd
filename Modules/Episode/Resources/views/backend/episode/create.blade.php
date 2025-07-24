@extends('backend.layouts.app')

@section('content')
<x-back-button-component route="backend.episodes.index" />
<p class="text-danger" id="error_message"></p>
@if(isenablemodule('enable_tmdb_api')==1)

<div class="mb-3">

    <div class="d-flex align-items-end justify-content-between gap-3 mb-3">
        <div class="flex-grow-1">
            <div class="row">
                {{ html()->label(__('movie.import_episode'))->class('form-label form-control-label') }}
                <div class="col-lg-4">
                    {{ html()->label(__('movie.tvshows'), 'tvshows')->class('form-label') }}
                    {{ html()->select(
                    'tv_show_id',
                    $imported_tvshow->pluck('name', 'tmdb_id')->prepend(__('placeholder.lbl_select_tvshow'), null),

                    )->class('form-control select2')->id('tv_show_id') }}
                    <span class="text-danger" id="tvshow_id_error"></span>
                </div>
                <div class="col-lg-4">
                    {{ html()->label(__('movie.seasons'), 'seasons')->class('form-label') }}
                    {{ html()->select(
                    'season_index',
                    null,

                    )->class('form-control select2')->id('season_index') }}
                    <span class="text-danger" id="season_index_error"></span>
                </div>
                <div class="col-lg-4">
                    {{ html()->label(__('episode.episode'), 'episode')->class('form-label') }}
                    {{ html()->select(
                    'episode_index',
                    null,

                    )->class('form-control select2')->id('episode_index') }}
                    <span class="text-danger" id="episode_index_error"></span>
                </div>
            </div>
        </div>
        <div>
            <div id="loader" style="display: none;">
                <button class="btn btn-md btn-primary float-right">{{__('tvshow.lbl_loading')}}</button>
            </div>
            <button class="btn btn-md btn-primary float-right" id="import_episode">{{__('tvshow.lbl_import')}}</button>
        </div>
    </div>
</div>
@endif


{{ html()->form('POST' ,route('backend.episodes.store'))
    ->attribute('enctype', 'multipart/form-data')
    ->attribute('data-toggle', 'validator')
    ->attribute('id', 'form-submit')  // Add the id attribute here
    ->class('requires-validation')  // Add the requires-validation class
    ->attribute('novalidate', 'novalidate')  // Disable default browser validation
    ->open()
}}
    @csrf

    <div class="d-flex align-items-center justify-content-between mt-5 pt-4 mb-3">
        <h6>{{__('customer.about')}} {{__('episode.episode')}}</h6>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row gy-3">
                {{ html()->hidden('episode_number', null)->id('episode_number') }}
                {{ html()->hidden('tmdb_season', null)->id('tmdb_season') }}
                {{ html()->hidden('tmdb_id', null)->id('tmdb_id') }}
                <div class="col-lg-4">
                    <div class="position-relative">
                        {{ html()->label(__('movie.lbl_poster'), 'poster')->class('form-label form-control-label') }}
                        <div class="input-group btn-file-upload">
                            {{ html()->button('<i class="ph ph-image"></i>'. __('messages.lbl_choose_image'))
                            ->class('input-group-text form-control')
                            ->type('button')
                            ->attribute('data-bs-toggle', 'modal')
                            ->attribute('data-bs-target', '#exampleModal')
                            ->attribute('data-image-container', 'selectedImageContainerPoster')
                            ->attribute('data-hidden-input', 'poster_url')->style('height:13.6rem')
                            }}

                            {{ html()->text('poster_input')
                            ->class('form-control')
                            ->placeholder('placeholder.lbl_image')
                            ->attribute('aria-label', 'Poster Image')
                            ->attribute('data-bs-toggle', 'modal')
                            ->attribute('data-bs-target', '#exampleModal')
                            ->attribute('data-image-container', 'selectedImageContainerPoster')
                            ->attribute('data-hidden-input', 'poster_url')
                             }}
                             {{ html()->hidden('poster_url')->id('poster_url')->value(old('poster_url', isset($data) ? $data->poster_url : '')) }}
                        </div>
                        <div class="uploaded-image" id="selectedImageContainerPoster">

                            <img id="selectedPosterImage"
                                src="{{ old('poster_url', isset($data) ? $data->poster_url : '') }}" alt="feature-image"
                                class="img-fluid mb-2 avatar-80 "
                                style="{{ old('poster_url', isset($data) ? $data->poster_url : '') ? '' : 'display:none;' }}" />

                            {{-- @if(old('poster_url', isset($data) ? $data->poster_url : ''))
                            <img src="{{ old('poster_url', isset($data) ? $data->poster_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                            @endif --}}

                            {{ html()->hidden('poster_url')->id('file_url_poster')->value(old('poster_url', isset($data) ? $data->poster_url : '')) }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row gy-3">
                        <div class="col-md-6">
                            {{ html()->label(__('season.lbl_tv_shows') . ' <span class="text-danger">*</span>', 'type')->class('form-label') }}
                            {{ html()->select(
                                        'entertainment_id',
                                        $tvshows->pluck('name', 'id')->prepend(__('placeholder.lbl_select_tvshow'),''), old('entertainment_id'))->class('form-control select2')->id('entertainment_id')->attribute('required','required') }}
                            @error('entertainment_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="invalid-feedback" id="name-error">TV Show field is required</div>
                        </div>
                        <div class="col-md-6">
                            {{ html()->label(__('episode.lbl_season') . ' <span class="text-danger">*</span>', 'season_id')->class('form-label') }}
                            {{ html()->select(
                                        'season_id',
                                        $seasons->pluck('name', 'id')->prepend(__('placeholder.lbl_select_season'),''),old('season_id'))->class('form-control select2')->id('season_id')->attribute('required','required') }}
                            @error('season_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="invalid-feedback" id="name-error">Season field is required</div>
                        </div>
                        <div class="col-md-6">
                            {{ html()->label(__('movie.lbl_name') . ' <span class="text-danger">*</span>', 'name')->class('form-label') }}
                            {{ html()->text('name')->attribute('value', old('name'))->placeholder(__('placeholder.lbl_episode_name'))->class('form-control')->attribute('required','required') }}
                            <span class="text-danger" id="error_msg"></span>
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="invalid-feedback" id="name-error">Name field is required</div>
                        </div>
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
                        <div class="col-md-6">
                            {{ html()->label(__('movie.lbl_movie_access') , 'access')->class('form-label') }}
                            <div class="d-flex align-items-center gap-3">
                                <label class="form-check form-check-inline form-control px-5 cursor-pointer">
                                <div >
                                    <input class="form-check-input" type="radio" name="access" id="paid" value="paid" onchange="showPlanSelection(this.value === 'paid')" {{ old('access') == 'paid' ? 'checked' : '' }} checked>
                                    <span class="form-check-label" >{{__('movie.lbl_paid')}}</span>
                                </div>
                            </label>
                                <label class="form-check form-check-inline form-control px-5 cursor-pointer">
                                <div >
                                    <input class="form-check-input" type="radio" name="access" id="free" value="free" onchange="showPlanSelection(this.value === 'paid')" {{ old('access') == 'free' ? 'checked' : '' }}>
                                    <span class="form-check-label" >{{__('movie.lbl_free')}}</span>
                                </div>
                            </div>
                        </label>

                            @error('access')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 {{ old('access', 'paid') == 'free' ? 'd-none' : '' }}" id="planSelection">
                            {{ html()->label(__('movie.lbl_select_plan'). ' <span class="text-danger">*</span>', 'type')->class('form-label') }}
                            {{ html()->select('plan_id', $plan->pluck('name', 'id')->prepend(__('placeholder.lbl_select_plan'), ''), old('plan_id'))->class('form-control select2')->id('plan_id') }}
                            @error('plan_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="invalid-feedback" id="name-error">Plan field is required</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        {{ html()->label(__('movie.lbl_short_desc'), 'short_desc')->class('form-label') }}
                        <span class="text-primary cursor-pointer" id="GenrateshortDescription" ><i class="ph ph-info" data-bs-toggle="tooltip" title="{{ __('messages.chatgpt_info') }}"></i> {{ __('messages.lbl_chatgpt') }}</span>
                    </div>
                    {{ html()->textarea('short_desc', old('short_desc'))->class('form-control')->id('short_desc')->placeholder(__('placeholder.episode_short_desc'))->rows(8) }}
                    @error('short_desc')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-lg-6">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        {{ html()->label(__('movie.lbl_description'). '<span class="text-danger"> *</span>', 'description')->class('form-label mb-0') }}
                        <span class="text-primary cursor-pointer" id="GenrateDescription" ><i class="ph ph-info" data-bs-toggle="tooltip" title="{{ __('messages.chatgpt_info') }}"></i> {{ __('messages.lbl_chatgpt') }}</span>
                    </div>
                    {{ html()->textarea('description', old('description'))->class('form-control')->id('description')->placeholder(__('placeholder.lbl_movie_description'))->rows(4)->attribute('required','required') }}
                    @error('description')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="invalid-feedback" id="desc-error">Description field is required</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    {{ html()->label(__('movie.lbl_trailer_url_type').' <span class="text-danger">*</span>', 'type')->class('form-label') }}
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
                <div class="col-md-6 col-lg-4 d-none" id="url_input">
                    {{ html()->label(__('movie.lbl_trailer_url').' <span class="text-danger">*</span>', 'trailer_url')->class('form-label') }}
                    {{ html()->text('trailer_url')->attribute('value', old('trailer_url'))->placeholder(__('placeholder.lbl_trailer_url'))->class('form-control') }}
                    @error('trailer_url')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="invalid-feedback" id="trailer-url-error">Video URL field is required</div>
                    <div class="invalid-feedback" id="trailer-pattern-error" style="display:none;">
                    Please enter a valid URL starting with http:// or https://.
                </div>
                </div>
                <div class="col-md-6 col-lg-4 d-none" id="url_file_input">
                    {{ html()->label(__('movie.lbl_trailer_video').' <span class="text-danger">*</span>', 'trailer_video')->class('form-label') }}
                    <div class="" id="selectedImageContainertailerurl">
                        @if(old('trailer_url', isset($data) ? $data->trailer_url : ''))
                        <img src="{{ old('trailer_url', isset($data) ? $data->trailer_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                        @endif
                    </div>

                    <div class="input-group btn-video-link-upload">
                        {{ html()->button(__('placeholder.lbl_select_file').'<i class="ph ph-upload"></i>')
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
                    <div class="" id="selectedImageContainertailerurl">
                        @if (old('trailer_url', isset($data) ? $data->trailer_url : ''))
                            <img src="{{ old('trailer_url', isset($data) ? $data->trailer_url : '') }}"
                                class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                        @endif
                    </div>

                    {{ html()->hidden('trailer_video')->id('file_url_trailer')->value(old('trailer_url', isset($data) ? $data->trailer_url : ''))->attribute('data-validation', 'iq_video_quality')  }}

                    @error('trailer_video')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="invalid-feedback" id="trailer-file-error">Video File field is required</div>

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
                    {{ html()->label(__('movie.lbl_duration') . ' <span class="text-danger">*</span>', 'duration')->class('form-label') }}
                    {{ html()->time('duration')->attribute('value', old('duration'))->placeholder(__('movie.lbl_duration'))->class('form-control min-datetimepicker-time')->attribute('required','required') }}
                    @error('duration')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="invalid-feedback" id="duration-error">Duration field is required</div>
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
                    {{ html()->label(__('movie.lbl_release_date') . '<span class="text-danger">*</span>' , 'release_date')->class('form-label') }}
                    {{ html()->date('release_date')->attribute('value', old('release_date'))->placeholder(__('movie.lbl_release_date'))->class('form-control datetimepicker')->attribute('required','required') }}
                    @error('release_date')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="invalid-feedback" id="release_date-error">Release Date field is required</div>
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
                        {{ html()->label(__('messages.on'), 'download_status')->class('form-label mb-0 text-body') }}
                        <div class="form-check form-switch">
                            {{ html()->hidden('download_status', 0) }}
                            {{ html()->checkbox('download_status', old('download_status', 1))->class('form-check-input')->id('download_status')->value(1) }}
                        </div>
                    </div>
                    @error('download_status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mt-5 pt-4 mb-3">
        <h5>{{ __('movie.lbl_video_info') }}</h5>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row gy-3">
                <div class="col-md-6 col-lg-6">
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
                <div class="col-md-6 col-lg-6 d-none" id="video_url_input_section">
    {{ html()->label(__('movie.video_url_input'). '<span class="text-danger">*</span>', 'video_url_input')->class('form-control-label') }}
    {{
        html()->text('video_url_input')
            ->attribute('value', old('video_url_input'))
            ->placeholder(__('placeholder.video_url_input'))
            ->class('form-control')

    }}
    @error('video_url_input')
    <span class="text-danger">{{ $message }}</span>
    @enderror
    <div class="invalid-feedback" id="url-error">Video URL field is required</div>
    <div class="invalid-feedback" id="url-pattern-error" style="display:none;">
                            Please enter a valid URL starting with http:// or https://.
                        </div>
</div>
                <div class="col-md-6 col-lg-6 d-none" id="video_file_input_section">
                    {{ html()->label(__('movie.video_file_input'). '<span class="text-danger">*</span>', 'video_file')->class('form-label') }}

                    <div class="input-group btn-video-link-upload">
                        {{ html()->button(__('placeholder.lbl_select_file').'<i class="ph ph-upload"></i>')
                                ->class('input-group-text form-control')
                                ->type('button')
                                ->attribute('data-bs-toggle', 'modal')
                                ->attribute('data-bs-target', '#exampleModal')
                                ->attribute('data-image-container', 'selectedImageContainerVideourl')
                                ->attribute('data-hidden-input', 'file_url_video')
                            }}

                        {{ html()->text('video_file_input')
                                ->class('form-control')
                                ->placeholder('Select Image')
                                ->attribute('aria-label', 'Video Image')
                                ->attribute('data-bs-toggle', 'modal')
                                ->attribute('data-bs-target', '#exampleModal')
                                ->attribute('data-image-container', 'selectedImageContainerVideourl')
                                ->attribute('data-hidden-input', 'file_url_video')
                            }}
                    </div>
                    <div class="mt-3" id="selectedImageContainerVideourl">
                        @if (old('video_file_input'))
                            <img src="{{ old('video_file_input') }}"
                                class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                        @endif
                    </div>

                    {{ html()->hidden('video_file_input')->id('file_url_video')->value(old('video_file_input'))->attribute('data-validation', 'iq_video_quality')  }}


                    @error('video')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="invalid-feedback" id="file-error">Video File field is required</div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mt-5 pt-4 mb-3">
        <h5>{{ __('movie.lbl_quality_info') }}</h5>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row gy-3">
                <div class="col-lg-12">
                    <label for="enable_quality" class="form-label">{{ __('movie.lbl_enable_quality') }}</label>
                    <div class="d-flex justify-content-between align-items-center form-control">
                        <label for="enable_quality" class="form-label mb-0 text-body">{{ __('movie.lbl_enable_quality') }}</label>
                        <div class="form-check form-switch">
                            <input type="hidden" name="enable_quality" value="0">
                            <input type="checkbox" name="enable_quality" id="enable_quality" class="form-check-input" value="1" {{ old('enable_quality', false) ? 'checked' : '' }} onchange="toggleQualitySection()">
                        </div>
                    </div>
                    @error('enable_quality')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-12">
                    <div id="enable_quality_section" class="enable_quality_section d-none">
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
                                    {{ html()->select(
                                                'video_quality[]',
                                                $video_quality->pluck('name', 'value')->prepend(__('placeholder.lbl_select_quality'), '')
                                            )->class('form-control select2 video_quality') }}
                                </div>

                                <div class="col-md-4 d-none video-url-input quality_video_input">
                                    {{ html()->label(__('movie.video_url_input'), 'quality_video_url_input')->class('form-control-label') }}
                                    {{ html()->text('quality_video_url_input[]')->placeholder(__('placeholder.video_url_input'))->class('form-control') }}
                                </div>

                                <div class="col-md-4 d-none video-file-input quality_video_file_input">
                                    {{ html()->label(__('movie.video_file_input'), 'quality_video')->class('form-label') }}

                                    <div class="input-group btn-video-link-upload">
                                        {{ html()->button(__('placeholder.lbl_select_file').'<i class="ph ph-upload"></i>')
                                                ->class('input-group-text form-control')
                                                ->type('button')
                                                ->attribute('data-bs-toggle', 'modal')
                                                ->attribute('data-bs-target', '#exampleModal')
                                                ->attribute('data-image-container', 'selectedImageContainerVideoqualityurl')
                                                ->attribute('data-hidden-input', 'file_url_videoquality')
                                            }}

                                        {{ html()->text('videoquality_input')
                                                ->class('form-control')
                                                ->placeholder('Select Image')
                                                ->attribute('aria-label', 'Video Quality Image')
                                                ->attribute('data-bs-toggle', 'modal')
                                                ->attribute('data-bs-target', '#exampleModal')
                                                ->attribute('data-image-container', 'selectedImageContainerVideoqualityurl')
                                                ->attribute('data-hidden-input', 'file_url_videoquality')
                                            }}
                                    </div>
                                    <div class="mt-3" id="selectedImageContainerVideoqualityurl">
                                        @if(old('video_quality_url', isset($data) ? $data->video_quality_url : ''))
                                        <img src="{{ old('video_quality_url', isset($data) ? $data->video_quality_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                                        @endif
                                    </div>

                                    {{ html()->hidden('quality_video[]')->id('file_url_videoquality')->value(old('video_quality_url', isset($data) ? $data->video_quality_url : ''))->attribute('data-validation', 'iq_video_quality') }}
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
            var planIdSelect = document.getElementById('plan_id');
            var planSelection = document.getElementById('planSelection');
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

        document.addEventListener('DOMContentLoaded', function() {
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
                    videofile.removeAttribute('required');
                    videourl.removeAttribute('required');
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
                    }
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


        function getSeasons(entertainmentId, selectedSeasonId = "") {

            var get_seasons_list = "{{ route('backend.seasons.index_list', ['entertainment_id' => '']) }}" + entertainmentId;
            get_seasons_list = get_seasons_list.replace('amp;', '');

            $.ajax({
                url: get_seasons_list,
                success: function(result) {

                    var formattedResult = result.map(function(season) {
                        return {
                            id: season.id,
                            text: season.name
                        };
                    });

                    $('#season_id').select2({
                        width: '100%',
                        placeholder: "{{ trans('messages.select_name', ['select' => trans('messages.season')]) }}",
                        data: formattedResult
                    });

                    if (selectedSeasonId != "") {
                        $('#season_id').val(selectedSeasonId).trigger('change');
                    }
                }
            });
        }

        $(document).ready(function() {
            $('#entertainment_id').change(function() {
                var entertainmentId = $(this).val();
                if (entertainmentId) {
                    $('#season_id').empty().select2({
                        width: '100%',
                        placeholder: "{{ trans('messages.select_name', ['select' => trans('messages.season')]) }}"
                    });
                    getSeasons(entertainmentId);
                } else {
                    $('#season_id').empty().select2({
                        width: '100%',
                        placeholder: "{{ trans('messages.select_name', ['select' => trans('messages.season')]) }}"
                    });
                }
            });
        });




        function getimportSeasons(tmdbId, selectedSeasonId = "") {
            var get_seasons_list = "{{ route('backend.episodes.import-season-list', ['tmdb_id' => '']) }}" + tmdbId;
            get_seasons_list = get_seasons_list.replace('amp;', '');

            $.ajax({
                url: get_seasons_list,
                success: function(result) {
                    var formattedResult = result.map(function(season) {
                        return {
                            id: season.season_index,
                            text: season.name
                        };
                    });

                    formattedResult.unshift({
                        id: '',
                        text: "{{ trans('episode.select_seson', ['select' => trans('messages.season')]) }}"
                    });

                    $('#season_index').select2({
                        width: '100%',
                        placeholder: "{{ trans('episode.select_seson', ['select' => trans('messages.season')]) }}",
                        data: formattedResult
                    });

                    if (selectedSeasonId != "") {
                        $('#season_index').val(selectedSeasonId).trigger('change');
                    }
                }
            });
        }

        $(document).ready(function() {
            $('#tv_show_id').change(function() {
                var tvShowId = $(this).val();
                if (tvShowId) {
                    $('#season_index').empty().select2({
                        width: '100%',
                        placeholder: "{{ trans('episode.select_seson', ['select' => trans('messages.season')]) }}"
                    });
                    getimportSeasons(tvShowId);
                } else {
                    $('#season_index').empty().select2({
                        width: '100%',
                        placeholder: "{{ trans('episode.select_seson', ['select' => trans('messages.season')]) }}"
                    });
                }
            });
        });


        function getEpisode(tvShowId, season_index, selectedEpisodeId = "") {
            var get_episode_list = "{{ route('backend.episodes.import-episode-list') }}";
            get_episode_list = get_episode_list.replace('amp;', '');

            $.ajax({
                url: get_episode_list,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    tvshow_id: tvShowId,
                    season_id: season_index,
                },
                success: function(result) {
                    var formattedResult = result.map(function(episode) {
                        return {
                            id: episode.episode_number,
                            text: episode.name
                        };
                    });

                    formattedResult.unshift({
                        id: '',
                        text: "{{ trans('episode.select_episode', ['select' => trans('messages.episode')]) }}"
                    });

                    $('#episode_index').select2({
                        width: '100%',
                        placeholder: "{{ trans('episode.select_episode', ['select' => trans('messages.episode')]) }}",
                        data: formattedResult
                    });

                    if (selectedEpisodeId != "") {
                        $('#episode_index').val(selectedEpisodeId).trigger('change');
                    }
                }
            });
        }

        $(document).ready(function() {
            $('#season_index').change(function() {
                var season_index = $(this).val();
                var tvShowId = $('#tv_show_id').val();

                if (season_index) {
                    $('#episode_index').empty().select2({
                        width: '100%',
                        placeholder: "{{ trans('episode.select_episode', ['select' => trans('messages.episode')]) }}"
                    });
                    getEpisode(tvShowId, season_index);
                } else {
                    $('#episode_index').empty().select2({
                        width: '100%',
                        placeholder: "{{ trans('episode.select_episode', ['select' => trans('messages.episode')]) }}"
                    });
                }
            });
        });



        $(document).ready(function() {
            $('#import_episode').on('click', function(e) {
                e.preventDefault();

                var tvshowID = $('#tv_show_id').val();
                $('#tvshow_id_error').text('');
                $('#error_message').text('');


                var seasonID = $('#season_index').val();
                $('#season_index_error').text('');
                $('#error_message').text('');


                var episodeID = $('#episode_index').val();
                $('#episode_index_error').text('');
                $('#error_message').text('');


                var import_episode = "{{ route('backend.episodes.import-episode') }}";
                import_episode = import_episode.replace('amp;', '');

                if (!tvshowID) {
                    $('#tvshow_id_error').text('TV show ID is required.');
                    return;
                }

                if (!seasonID) {
                    $('#season_index_error').text('Season is required.');
                    return;
                }

                if (!episodeID) {
                    $('#episode_index_error').text('Season is required.');
                    return;
                }

                $('#loader').show();
                $('#import_episode').hide();

                $.ajax({
                    url: import_episode,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        tvshow_id: tvshowID,
                        season_id: seasonID,
                        episode_id: episodeID,
                    },
                    success: function(response) {

                        $('#loader').hide();
                        $('#import_episode').show();

                        if (response.success) {

                            var data = response.data;
                            $('#tmdb_season').val(data.tmdb_season);
                            $('#episode_number').val(data.episode_number);
                            $('#tmdb_id').val(data.tmdb_id);
                            $('#selectedPosterImage').attr('src', data.poster_url).show();
                            $('#name').val(data.name);
                            $('#description').val(data.description);
                            $('#trailer_url').val(data.trailer_url);
                            $('#trailer_url_type').val(data.trailer_url_type).trigger('change');
                            $('#entertainment_id').val(data.entertainment_id).trigger('change');
                            $('#season_id').val(data.season_id).trigger('change');
                            $('#release_date').val(data.release_date);
                            $('#duration').val(data.duration);
                            $('#file_url_poster').val(data.poster_url);
                            $('#video_upload_type').val(data.video_url_type).trigger('change');
                            $('#video_url_input').val(data.video_url);


                            if (data.is_restricted) {
                                $('#is_restricted').prop('checked', true).val(1);
                            } else {
                                $('#is_restricted').prop('checked', false).val(0);
                            }


                            if (data.poster_url) {

                                $('#selectedPosterImage').attr('src', data.poster_url).show();
                            }
                            if (data.movie_access === 'paid') {
                                document.getElementById('paid').checked = true;
                                showPlanSelection(true);
                            } else {

                                document.getElementById('free').checked = true;
                                showPlanSelection(false);
                            }

                            if (data.enable_quality === true) {

                                $('#enable_quality').prop('checked', true).val(1);
                            } else {

                                $('#enable_quality').prop('checked', false).val(0);
                            }

                            toggleQualitySection()

                            if (data.enable_quality === true) {


                                const container = document.getElementById('video-inputs-container-parent');
                                container.innerHTML = ''; // Clear existing content

                                data.episodeStreamContentMappings.forEach((video, index) => {
                                    const videoInputContainer = document.createElement('div');
                                    videoInputContainer.className = 'row video-inputs-container';

                                    videoInputContainer.innerHTML = `
          <div class="col-sm-3 mb-3">
            <label class="form-label" for="video_quality_type_${index}">Video Upload Type</label>
            <select name="video_quality_type[]" id="video_quality_type_${index}" class="form-control select2 video_quality_type">
              <option value="YouTube" ${video.video_quality_type === 'YouTube' ? 'selected' : ''}>YouTube</option>
              <option value="Local" ${video.video_quality_type === 'Local' ? 'selected' : ''}>Local</option>
            </select>
          </div>

          <div class="col-sm-3 mb-3 video-input">
            <label class="form-label" for="video_quality_${index}">Video Quality</label>
            <select name="video_quality[]" id="video_quality_${index}" class="form-control select2 video_quality">
              <option value="1080p" ${video.video_quality === 1080 ? 'selected' : ''}>1080p</option>
              <option value="720p" ${video.video_quality === 720 ? 'selected' : ''}>720p</option>
              <option value="480p" ${video.video_quality === 480 ? 'selected' : ''}>480p</option>
            </select>
          </div>

          <div class="col-sm-3 mb-3 video-url-input quality_video_input">
            <label class="form-control-label" for="quality_video_url_input_${index}">Video URL</label>
            <input type="text" name="quality_video_url_input[]" id="quality_video_url_input_${index}" placeholder="Enter video URL" class="form-control" value="${video.quality_video}">
          </div>

          <div class="col-sm-3 mb-3 d-none video-file-input quality_video_file_input">
            <label class="form-control-label" for="quality_video_${index}">Video File</label>
            <input type="file" name="quality_video[]" id="quality_video_${index}" class="form-control-file" accept="video/*">
          </div>

          <div class="col-sm-12 mb-3">
            <button type="button" class="btn btn-danger remove-video-input">Remove</button>
          </div>
        `;

                                    container.appendChild(videoInputContainer);
                                });
                            } else {

                                $('#enable_quality').prop('checked', false).val(0);
                                $('#enable_quality_section').addClass('d-none');
                            }

                        } else {
                            $('#error_message').text(response.message || 'Failed to import movie details.');
                        }
                    },
                    error: function(xhr) {
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

$('#GenrateshortDescription').on('click', function(e) {

    e.preventDefault();

    var description = $('#short_desc').val();
    var name = $('#name').val();
    var tvshow = $('#entertainment_id').val();
    var season = $('#season_id').val();
    var type = 'short_desc';



    var generate_discription = "{{ route('backend.episodes.generate-description') }}";
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
               tvshow: tvshow,
               season:season,
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
    var season = $('#season_id').val();
    var type = null;


    var generate_discription = "{{ route('backend.episodes.generate-description') }}";
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
               season:season

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
