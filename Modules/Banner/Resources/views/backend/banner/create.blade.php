@extends('backend.layouts.app')

@section('content')
<x-back-button-component route="backend.banners.index" />

{{ html()->form('POST', route('backend.banners.store'))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle', 'validator')
    ->attribute('id', 'form-submit')
    ->class('requires-validation')
    ->attribute('novalidate', 'novalidate')
    ->open() }}
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row gy-3">
                <div class="col-md-6 col-lg-3">
                    <div class="position-relative">
                        {{ html()->label(__('banner.lbl_image'), 'file_url')->class('form-label') }}
                        <div class="input-group btn-file-upload">
                            {{ html()->button('<i class="ph ph-image"></i>' . __('messages.lbl_choose_image'))
                                ->class('input-group-text form-control')
                                ->type('button')
                                ->attribute('data-bs-toggle', 'modal')
                                ->attribute('data-bs-target', '#exampleModal')
                                ->attribute('data-image-container', 'selectedImageContainerThumbnail')
                                ->attribute('data-hidden-input', 'file_url_image')
                                ->style('height:13.6rem') }}

                            {{ html()->text('thumbnail_input')
                                ->class('form-control')
                                ->placeholder(__('placeholder.lbl_image'))
                                ->attribute('aria-label', 'Thumbnail Image') }}

                            {{ html()->hidden('file_url')->id('file_url_image')->value(old('file_url', isset($data) ? $data->file_url : '')) }}
                        </div>
                        <div class="uploaded-image" id="selectedImageContainerThumbnail">
                            @if(old('file_url', isset($data) ? $data->file_url : ''))
                                <img src="{{ old('file_url', isset($data) ? $data->file_url : '') }}" class="img-fluid avatar-150">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="position-relative">
                        {{ html()->label(__('movie.lbl_poster'), 'poster')->class('form-label') }}
                        <div class="input-group btn-file-upload">
                            {{ html()->button('<i class="ph ph-image"></i>' . __('messages.lbl_choose_image'))
                                ->class('input-group-text form-control')
                                ->type('button')
                                ->attribute('data-bs-toggle', 'modal')
                                ->attribute('data-bs-target', '#exampleModal')
                                ->attribute('data-image-container', 'selectedImageContainerPoster')
                                ->attribute('data-hidden-input', 'poster_url')
                                ->style('height:13.6rem') }}

                            {{ html()->text('poster_input')->class('form-control')->placeholder('placeholder.lbl_image')->attribute('aria-label', 'Poster Image') }}

                            {{ html()->hidden('poster_url')->id('poster_url')->value(old('poster_url', isset($data) ? $data->poster_url : '')) }}
                        </div>
                        <div class="uploaded-image" id="selectedImageContainerPoster">
                            @if(old('poster_url', isset($data) ? $data->poster_url : ''))
                                <img src="{{ old('poster_url', isset($data) ? $data->poster_url : '') }}" class="img-fluid avatar-150">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        {{ html()->label(__('banner.lbl_type') . '<span class="text-danger">*</span>', 'type')->class('form-label') }}
                        {{ html()->select('type', ['' => __('placeholder.lbl_select_type')] + $types, old('type'))->class('form-control select2')->id('type')->attribute('required','required') }}
                        @error('type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="name-error">Type field is required</div>
                    </div>
                    {{ html()->hidden('type_id')->id('type_id') }}
                    {{ html()->hidden('type_name')->id('type_name') }}
                    <div class="">
                        {{ html()->label(__('banner.lbl_name') . '<span class="text-danger">*</span>', 'name')->class('form-label') }}
                        {{ html()->select('name_id', ['' => __('messages.select_name')] + [], old('name_id'))->class('form-control select2')->id('name_id')->attribute('required','required') }}
                        @error('name_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="invalid-feedback" id="name-error">Name field is required</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">

                    <div class="">
                        {{ html()->label(__('banner.lbl_status'), 'status')->class('form-label') }}
                        <div class="d-flex justify-content-between align-items-center form-control">
                            {{ html()->label(__('messages.active'), 'status')->class('form-label mb-0 text-body') }}
                            <div class="form-check form-switch">
                                {{ html()->hidden('status', 0) }}
                                {{ html()->checkbox('status', old('status', 1))->class('form-check-input')->id('status')->value(1) }}
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-grid d-sm-flex justify-content-sm-end gap-3">
        {{ html()->submit(__('messages.save'))->class('btn btn-md btn-primary float-right')->id('submit-button') }}
    </div>
    {{ html()->form()->close() }}

    @include('components.media-modal')
@endsection

@push('after-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function readURL(input, imgElement) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    imgElement.attr('src', e.target.result).show();
                    $('#removeImageBtn').removeClass('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $('#file_url').change(function() {
            readURL(this, $('#selectedImage'));
        });

        $('#removeImageBtn').click(function() {
            $('#selectedImage').attr('src', '').hide();
            $('#file_url').val('');
            $(this).addClass('d-none');
        });
    });

    function getNames(type, selectedNameId = "") {
        var get_names_list = "{{ route('backend.banners.index_list', ['type' => ':type']) }}".replace(':type', type);

        $.ajax({
            url: get_names_list,
            success: function(result) {
                var formattedResult = [{ id: '', text: "{{ __('messages.select_name') }}" }]; // Default option

                var names = result.map(function(item) {
                    return {
                        id: item.id,
                        text: item.name,
                        thumbnail_url: item.thumbnail_url,
                        poster_url: item.poster_url
                    };
                });

                formattedResult = formattedResult.concat(names); // Append fetched names

                $('#name_id').select2({
                    width: '100%',
                    data: formattedResult
                });

                if (selectedNameId != "") {
                    $('#name_id').val(selectedNameId).trigger('change');
                }
            }
        });
    }

    $(document).ready(function() {
        $('#type').change(function() {
            var type = $(this).val();
            var typeName = $('#type option:selected').text();

            if (type) {
                $('#type_id').val(type);
                $('#type_name').val(typeName);

                $('#name_id').empty();
                getNames(type);
            } else {
                $('#name_id').empty();
            }
        });

        $('#name_id').change(function() {
            var selectedNameId = $(this).val();
            var selectedNameText = $('#name_id option:selected').text();

            if (selectedNameId) {
                $('#type_id').val(selectedNameId);
                $('#type_name').val(selectedNameText);
            } else {
                $('#type_id').val('');
                $('#type_name').val('');
            }
        });

        $('#name_id').change(function() {
            var selectedOption = $('#name_id').select2('data')[0];
            if (selectedOption) {
                var thumbnailUrl = selectedOption.thumbnail_url;
                var posterUrl = selectedOption.poster_url;


                if (posterUrl) {
                    $('#file_url_image').val(posterUrl);
                    $('#selectedImageContainerThumbnail').html(`<img src="${posterUrl}" class="img-fluid avatar-150">`);
                } else {
                    $('#selectedImageContainerThumbnail').html('');
                    $('#file_url_image').val('');
                }

                if (thumbnailUrl) {
                    $('#poster_url').val(thumbnailUrl);
                    $('#selectedImageContainerPoster').html(`<img src="${thumbnailUrl}" class="img-fluid avatar-150">`);
                } else {
                    $('#selectedImageContainerPoster').html('');
                    $('#poster_url').val('');
                }
            }
        });
    });


</script>
@endpush

