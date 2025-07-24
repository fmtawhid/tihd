

@foreach ($moreinfinity as $category)
<div class="moreinfinity-card">
    <div class="d-flex align-items-center justify-content-between my-2 me-2">
        <h5 class="main-title text-capitalize mb-0">{{ $category['name'] }}</h5>
        <a href="{{ route('livetv-channels', ['id' => $category['id']]) }}" class="view-all-button text-decoration-none flex-none atv">
            <span>{{__('frontend.view_all')}}</span>
            <i class="ph ph-caret-right"></i>
        </a>
    </div>
    <div class="card-style-slider">
        <div class="slick-general" data-items="5.5" data-items-desktop="5" data-items-laptop="4.5" data-items-tab="3.5"
                data-items-mobile-sm="2.5" data-items-mobile="2.5" data-speed="1000" data-autoplay="false"
                data-center="false" data-infinite="false" data-navigation="true" data-pagination="false" data-spacing="12">
            
                @php 
                $channel_data = $category['channel_data']->toArray(request()); 
                @endphp
                @foreach ($channel_data as $resource)
            <div class="swiper-slide">
                @include('frontend::components.card.card_tv_category', ['moreinfinity_card' => $resource])
            </div>
            @endforeach
        </div>
    </div>
</div>
@endforeach
