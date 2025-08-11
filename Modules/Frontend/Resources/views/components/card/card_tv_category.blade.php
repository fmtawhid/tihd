<!-- <a href="{{ route('livetv-details', ['id' => $resource['id']]) }}">
<div class="livetv-card position-relative">
    <img src="{{ $resource['poster_image'] }}" alt="{{ $resource['name'] }}" class="livetv-img object-cover img-fluid w-100 rounded">
    <span class="live-card-badge">
        <span class="live-badge fw-semibold text-uppercase">{{__('frontend.live')}}</span>
    </span>
</div>
</a> -->
<a href="{{ route('livetv-details', ['id' => $resource['id']]) }}">
    <div class="livetv-card position-relative">
        <img src="{{ $resource['poster_image'] }}" 
             alt="{{ $resource['name'] }}" 
             style="max-width: 100%; height: auto; display: block;">
        <span class="live-card-badge">
            <span class="live-badge fw-semibold text-uppercase">{{ __('frontend.live') }}</span>
        </span>
    </div>
</a>
