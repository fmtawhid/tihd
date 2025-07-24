@extends('frontend::layouts.master')

@section('content')

<div id="thumbnail-section">
    @include('frontend::components.section.thumbnail',  ['data' => $data['trailer_url'] ,'type'=>$data['trailer_url_type'] ,'thumbnail_image'=>$data['poster_image']])
</div>

<div id="detail-section">
    @include('frontend::components.section.video_data',  ['data' => $data])
</div>

<div class="container-fluid">
    <div class="overflow-hidden">
        <div id="more-like-this">
            @include('frontend::components.section.video',  ['data' => $data['more_items'], 'title'=>__('frontend.more_like_this')])
        </div>
    </div>
</div>

<div class="modal fade" id="DeviceSupport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content position-relative">
            <div class="modal-body user-login-card m-0 p-4 position-relative">
                <button type="button" class="btn btn-primary custom-close-btn rounded-2" data-bs-dismiss="modal">
                    <i class="ph ph-x text-white fw-bold align-middle"></i>
                </button>

                <div class="modal-body">
                    {{__('frontend.device_not_support')}}
                  </div>

                <div class="d-flex align-items-center justify-content-center">
                    <a href="{{ Auth::check() ? route('subscriptionPlan') : route('login') }}" class="btn btn-primary mt-5" >{{__('frontend.upgrade')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
