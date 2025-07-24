@extends('frontend::layouts.master')

@section('meta')
<!-- Basic Meta Tags -->
<title>Programs - TI Channel | Voice of Islam</title>
<meta name="description" content="Explore a wide range of Islamic programs on TI Channel. Watch inspiring series, talks, and shows that foster spiritual growth, knowledge, and faith.">
<meta name="keywords" content="Islamic programs, TI Channel, Voice of Islam, Islamic series, faith-based shows, Islamic teachings, spirituality, Islamic TV platform, online Islamic content, Islamic OTT platform">
<meta name="author" content="TI Channel">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

<!-- Canonical Tag -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph Meta Tags (for social media) -->
<meta property="og:title" content="Programs - TI Channel | Voice of Islam">
<meta property="og:description" content="Explore a wide range of Islamic programs on TI Channel. Watch inspiring series, talks, and shows that foster spiritual growth, knowledge, and faith.">
<meta property="og:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="TI Channel">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Programs - TI Channel | Voice of Islam">
<meta name="twitter:description" content="Explore a wide range of Islamic programs on TI Channel. Watch inspiring series, talks, and shows that foster spiritual growth, knowledge, and faith.">
<meta name="twitter:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta name="twitter:site" content="@TI_Channel">
@endsection


@section('content')
<div class="list-page section-spacing-bottom px-0">
    <div class="page-title" id="page_title">
        @if (request()->route('language'))
            <h4 class="m-0 text-center"> {{ ucfirst(request()->route('language')) }} </h4>
        @elseif (request()->route('genre_id'))
            <h4 class="m-0 text-center"> {{ ucfirst($genre->name) }} </h4>
        @else
            <h4 class="m-0 text-center">{{__('frontend.movies')}}</h4>
        @endif

    </div>
    <div class="movie-lists">
        <div class="container-fluid">
            <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6" id="entertainment-list">
            </div>
            <div class="card-style-slider shimmer-container">
                <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                        @for ($i = 0; $i < 12; $i++)
                            <div class="shimmer-container col mb-3">
                                    @include('components.card_shimmer_movieList')
                            </div>
                        @endfor
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/entertainment.min.js') }}" defer></script>

<script>
    const noDataImageSrc = '{{ asset('img/NoData.png') }}';
    const envURL = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
    const shimmerContainer = document.querySelector('.shimmer-container');
    const EntertainmentList = document.getElementById('entertainment-list');
    const pageTitle = document.getElementById('page_title');
    let currentPage = 1;
    let isLoading = false;
    let hasMore = true;
    const per_page = 12;
    const csrf_token='{{ csrf_token() }}'
    const language = "{{ $language ?? '' }}";
    const genreId = "{{ $genre_id ?? '' }}"; // Get genre_id from the Blade template

    // Initialize the API URL
    let apiUrl = `${envURL}/api/movie-list?page=${currentPage}&is_ajax=1&per_page=${per_page}`;

    // Add query parameters only if they exist
    if (language) {
        apiUrl += `&language=${language}`;
    }
    if (genreId) {
        apiUrl += `&genre_id=${genreId}`;
    }

    const showNoDataImage = () => {
        shimmerContainer.innerHTML = '';
        const noDataImage = document.createElement('img');
        noDataImage.src = noDataImageSrc;
        noDataImage.alt = 'No Data Found';
        noDataImage.style.display = 'block';
        noDataImage.style.margin = '0 auto';
        shimmerContainer.appendChild(noDataImage);
    };

    const loadData = async () => {
        if (!hasMore || isLoading) return;

        isLoading = true;
        shimmerContainer.style.display = '';  // Show shimmer container
        try {
            const response = await fetch(`${apiUrl}&page=${currentPage}`);
            const data = await response.json();

            if (data?.html) {
                EntertainmentList.insertAdjacentHTML(currentPage === 1 ? 'afterbegin' : 'beforeend', data.html);
                hasMore = !!data.hasMore;
                if (hasMore) currentPage++;
                shimmerContainer.style.display = 'none';  // Hide shimmer container
                initializeWatchlistButtons();
            } else {
                showNoDataImage();
            }
        } catch (error) {
            console.error('Fetch error:', error);
            showNoDataImage();
        } finally {
            isLoading = false;
        }
    };

    const handleScroll = () => {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500 && hasMore) {
            loadData();
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        loadData();  // Load the first page of movies
        window.addEventListener('scroll', handleScroll);  // Attach scroll listener
        initializeWatchlistButtons()
    });

    function initializeWatchlistButtons() {

  const watchList = typeof isWatchList!== 'undefined' ? !!emptyWatchList : null;
  const watchListPresent = typeof emptyWatchList !== 'undefined' ? !!emptyWatchList : null;
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    $('.watch-list-btn').off('click').on('click', function () {

      var $this = $(this);
      var isInWatchlist = $this.data('in-watchlist');
      var entertainmentId = $this.data('entertainment-id');
      const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
      var entertainmentType = $this.data('entertainment-type'); // Get the type
      let action = isInWatchlist == '1' ? 'delete' : 'save';
      var data = isInWatchlist
          ? { id: [entertainmentId], _token: csrf_token }
          : { entertainment_id: entertainmentId, type: entertainmentType, _token: csrfToken };

      // Perform the AJAX request
      $.ajax({
          url: action === 'save' ? `${baseUrl}/api/save-watchlist` : `${baseUrl}/api/delete-watchlist?is_ajax=1`,
          method: 'POST',
          data: data,
          success: function (response) {
            window.successSnackbar(response.message)
              $this.find('i').toggleClass('ph-check ph-plus');
              $this.toggleClass('btn-primary btn-dark');
              $this.data('in-watchlist', !isInWatchlist);
              var newInWatchlist = !isInWatchlist ? 'true' : 'false';
              var newTooltip = newInWatchlist === 'true' ? 'Remove Watchlist' : 'Add Watchlist';

              // Destroy the current tooltip
              $this.tooltip('dispose');

              // Update the tooltip attribute
              $this.attr('data-bs-title', newTooltip);

              // Reinitialize the tooltip
              $this.tooltip();
              if (action !== 'save' && watchList) {
                $this.closest('.iq-card').remove();
                if (EntertainmentList.children.length === 0) {
                  if (watchListPresent) {
                    emptyWatchList.style.display = '';
                    const noDataImage = document.createElement('img');
                    noDataImage.src = noDataImageSrc;
                    noDataImage.alt = 'No Data Found';
                    noDataImage.style.display = 'block';
                    noDataImage.style.margin = '0 auto';
                    emptyWatchList.appendChild(noDataImage);
                }
                }
                // shimmerContainer.style.display = 'none';

            }
          },
          error: function (xhr) {
              if (xhr.status === 401) {
                  window.location.href = `${baseUrl}/login`;
              } else {
                  console.error(xhr);
              }
          }
      });
  });
  // Initialize tooltips for all watchlist buttons on page load
//    $('[data-bs-toggle="tooltip"]').tooltip();

}

</script>

@endsection
