
<div class="actor-detail-card d-flex align-items-center flex-md-row flex-column justify-center gap-md-5 gap-4 rounded-3">
    <img src="{{ $data['profile_image'] }}" class="img-fluid actor-img rounde-3 object-cover rounded" alt="Actor Images">
    <div>
        <p class="actor-description readmore-wrapper">
            <span class="readmore-text line-count-3">{!! $data['bio'] !!}It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</span>
            <span class="readmore-btn badge bg-dark cursor-pointer">{{__('frontend.read_more')}}</span>
        </p>
        <div class="d-flex flex-wrap align-items-center justify-contnet-center gap-md-5 gap-3 actor-desc">
            <div class="d-inline-flex align-items-center gap-3">
                <i class="ph ph-user"></i>
                <p class="mb-0 fw-medium">{{ $data['type'] }}</p>
            </div>
            <div class="d-inline-flex align-items-center gap-3">
                <i class="ph ph-cake"></i>
                <p class="mb-0 fw-medium">{{  $data['dob'] ? formatDate($data['dob']) : '-' }}</p>
            </div>
            <div class="d-inline-flex align-items-center gap-3">
                <i class="ph ph-map-pin-area"></i>
                <p class="mb-0 fw-medium">{{  $data['place_of_birth'] ? $data['place_of_birth'] : '-'  }}</p>
            </div>
        </div>
    </div>
</div>

