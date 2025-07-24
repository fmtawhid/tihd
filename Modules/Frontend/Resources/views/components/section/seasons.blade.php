<!-- <div id="season-card-wrapper" class="section-spacing-bottom px-0">
    <div class="seasons-tabs-wrapper position-relative">
        <div class="season-tabs-inner">
            <div class="left">
                <i class="ph ph-caret-left"></i>
            </div>
            <div class="season-tab-container custom-nav-slider">
                <ul class="nav nav-tabs season-tab" id="season-tab" role="tablist">
                    @foreach ($data as $index => $item)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                    id="season-{{ $index + 1 }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#season-{{ $index + 1 }}-pane"
                                    type="button"
                                    role="tab"
                                    aria-controls="season-{{ $index + 1 }}-pane"
                                    aria-selected="{{ $index == 0 ? 'true' : 'false' }} "> 
                                    
                                Season {{ $index + 1 }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="right">
                <i class="ph ph-caret-right"></i>
            </div>
        </div>

        <div class="tab-content" id="season-tab-content">
            @foreach($data as $index => $value)
                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                        id="season-{{ (int)$index + 1 }}-pane"
                        role="tabpanel"
                        aria-labelledby="season-{{ (int)$index + 1 }}"
                        tabindex="0">
                    <ul id="episode-list-{{ $value['season_id'] }}" class="list-inline m-0 p-0 d-flex flex-column gap-4 episode-list">
                        @foreach($value['episodes']->toArray(request()) as $episodeIndex => $episode)
                            <li>
                                @include('frontend::components.card.card_episode', ['data' => $episode, 'index' => $episodeIndex])
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if($value['total_episodes'] > 5)
                    <div class="viewmore-button-wrapper">
                        <button id="view-more-btn-{{ $value['season_id'] }}"
                                data-page="6"
                                data-season-id="{{ $value['season_id'] }}"
                                class="btn btn-dark view-more-btn">{{__('frontend.view_more')}}</button>
                        <button id="view-less-btn-{{ $value['season_id'] }}"
                                data-page="5"
                                data-season-id="{{ $value['season_id'] }}"
                                class="btn btn-secondary view-less-btn"
                                style="display: none;">{{__('frontend.view_less')}}</button>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

<script>

$(document).ready(function() {

    const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
    const apiUrl = `${baseUrl}/api/episode-list`;

    $('.view-more-btn').on('click', function() {
        let button = $(this);
        let showLessButton=$('.view-less-btn');
        let page = button.data('page');
        let seasonId = button.data('season-id');

        // Dynamically set the URL with query parameters
        let url = `${apiUrl}?per_page=${page}&season_id=${seasonId}&is_ajax=1`;

        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {

                if (response.status) {

                    $('#episode-list-' + seasonId).empty().append(response.html);

                    if (response.hasMore) {
                        button.data('page', page + 1);
                        showLessButton.show();
                    } else {
                        // If no more pages, hide the button
                        button.hide();
                    }
                } else {
                    console.log('No more episodes to load.');
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });
    $('.view-less-btn').on('click', function() {
        let button = $(this);
        let seasonId = button.data('season-id'); // Use data attribute for season ID

        // Reset the episode list
        $('#episode-list-' + seasonId).empty(); // Clear the current list
        let page = button.data('page');
        // Fetch the first page of episodes again
        let url = `${apiUrl}?per_page=5&season_id=${seasonId}&is_ajax=1`; // Request only the first two episodes
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.status) {
                    $('#episode-list-' + seasonId).append(response.html);
                    button.hide();
                    $('#view-more-btn-' + seasonId).data('page', 6).show(); // Show the View More button
                } else {
                    console.log('Failed to load initial episodes.');
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
        $(this).hide(); // Hide the View Less button
        $('#view-more-btn-' + seasonId).data('page', 6).show(); // Reset and show the View More button
    });

});
</script>
 -->
 <div id="season-card-wrapper" class="section-spacing-bottom px-0">
    <div class="seasons-tabs-wrapper position-relative">
        <div class="season-tabs-inner">
            <div class="left">
                <i class="ph ph-caret-left"></i>
            </div>
            <div class="season-tab-container custom-nav-slider">
                <ul class="nav nav-tabs season-tab" id="season-tab" role="tablist">
                    @foreach ($data as $index => $item)
                        <li class="nav-item" role="presentation">
                            <style>
                                /* শুরুতে সবার জন্য নিয়ম */
                                .tv-only {
                                    display: none;
                                }
                                .not-tv {
                                    display: block;
                                }
                                
                                /* এখন TV রেঞ্জের জন্য (৭৭৬px - ১১৯৯px) */
                                @media (min-width: 776px) and (max-width: 1199px) {
                                    .tv-only {
                                        display: block; /* TV হলে H4 দেখাবে */
                                    }
                                    .not-tv {
                                        display: none; /* TV হলে Button লুকাবে */
                                    }
                                }
                                </style>
                                
                                
                            {{-- Only show for TV --}}
                            <h4 class="tv-only">Season {{ $index + 1 }}</h4>

                            {{-- Show all device but hide for TV --}}
                            <button class="nav-link not-tv {{ $index == 0 ? 'active' : '' }}"
                                    id="season-{{ $index + 1 }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#season-{{ $index + 1 }}-pane"
                                    type="button"
                                    role="tab"
                                    aria-controls="season-{{ $index + 1 }}-pane"
                                    aria-selected="{{ $index == 0 ? 'true' : 'false' }}" 
                                    tabindex="{{ $index + 1 }}">
                                Season {{ $index + 1 }}
                            </button>

                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="right">
                <i class="ph ph-caret-right"></i>
            </div>
        </div>

        <div class="tab-content" id="season-tab-content">
            @foreach($data as $index => $value)
                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                        id="season-{{ (int)$index + 1 }}-pane"
                        role="tabpanel"
                        aria-labelledby="season-{{ (int)$index + 1 }}"
                        tabindex="0">
                    <ul id="episode-list-{{ $value['season_id'] }}" class="list-inline m-0 p-0 d-flex flex-column gap-4 episode-list">
                        @foreach($value['episodes']->toArray(request()) as $episodeIndex => $episode)
                            <li>
                                @include('frontend::components.card.card_episode', ['data' => $episode, 'index' => $episodeIndex])
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if($value['total_episodes'] > 5)
                    <div class="viewmore-button-wrapper">
                        <button id="view-more-btn-{{ $value['season_id'] }}"
                                data-page="6"
                                data-season-id="{{ $value['season_id'] }}"
                                class="btn btn-dark view-more-btn">{{__('frontend.view_more')}}</button>
                        <button id="view-less-btn-{{ $value['season_id'] }}"
                                data-page="5"
                                data-season-id="{{ $value['season_id'] }}"
                                class="btn btn-secondary view-less-btn"
                                style="display: none;">{{__('frontend.view_less')}}</button>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
    const apiUrl = `${baseUrl}/api/episode-list`;

    // Focus trap prevention handling
    let isFocusTrapped = false;

    // Track when focus enters the season tab
    $('.nav-link').on('focus', function() {
        isFocusTrapped = true; // Trap focus when season tab is focused
    });

    // Track when focus leaves the season tab
    $('.nav-link').on('blur', function() {
        isFocusTrapped = false; // Release focus trap when focus leaves season tab
    });

    // Prevent focus from going outside season tabs when focus is trapped
    $(document).on('keydown', function(e) {
        if (isFocusTrapped && e.key === 'Tab') {
            e.preventDefault(); // Prevent the tab key from moving focus outside
        }
    });

    // View more functionality
    $('.view-more-btn').on('click', function() {
        let button = $(this);
        let showLessButton = $('.view-less-btn');
        let page = button.data('page');
        let seasonId = button.data('season-id');

        let url = `${apiUrl}?per_page=${page}&season_id=${seasonId}&is_ajax=1`;

        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.status) {
                    $('#episode-list-' + seasonId).empty().append(response.html);
                    if (response.hasMore) {
                        button.data('page', page + 1);
                        showLessButton.show();
                    } else {
                        button.hide();
                    }
                } else {
                    console.log('No more episodes to load.');
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });

        // Ensure focus stays on the button after content is loaded
        button.focus();
    });

    $('.view-less-btn').on('click', function() {
        let button = $(this);
        let seasonId = button.data('season-id'); // Use data attribute for season ID

        // Reset the episode list
        $('#episode-list-' + seasonId).empty(); // Clear the current list
        let page = button.data('page');
        let url = `${apiUrl}?per_page=5&season_id=${seasonId}&is_ajax=1`; // Request only the first five episodes
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.status) {
                    $('#episode-list-' + seasonId).append(response.html);
                    button.hide();
                    $('#view-more-btn-' + seasonId).data('page', 6).show(); // Show the View More button
                } else {
                    console.log('Failed to load initial episodes.');
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
        $(this).hide(); // Hide the View Less button
        $('#view-more-btn-' + seasonId).data('page', 6).show(); // Reset and show the View More button
    });

});
</script>
