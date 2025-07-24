@extends('backend.layouts.app', ['isBanner' => false])

@section('title') {{ 'Dashboard' }} @endsection

@section('content')
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col-lg-8">
            <div class="row">
                <div class="col-md-4 col-sm-6">
                <a href="{{ route('backend.users.index') }}">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="card-icon mb-5 fs-1">
                                <i class="ph ph-user"></i>
                            </div>
                            <div class="card-data">
                                <h1 class="">{{ $totalusers }}</h1>
                                <p class="mb-0 fs-6">{{ __('dashboard.lbl_total_users') }}</p>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
                <!-- <div class="col-md-4 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="card-icon mb-5 fs-1">
                                <i class="ph ph-user-gear"></i>
                            </div>
                            <div class="card-data">
                                {{-- <h1 class="">{{ $activeusers }}</h1> --}}
                                {{-- <p class="mb-0 fs-6">{{ __('dashboard.lbl_active_users') }}</p> --}}
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="col-md-4 col-sm-6">
                    <a href="{{ route('backend.subscriptions.index') }}">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="card-icon mb-5 fs-1">
                                <i class="ph ph-users-three"></i>
                            </div>
                            <div class="card-data">
                                <h1 class="">{{ $totalSubscribers }}</h1>
                                <p class="mb-0 fs-6">{{ __('dashboard.lbl_total_subscribers') }}</p>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-md-4 col-sm-6">
                <a href="{{ route('backend.users.index', ['type' => 'soon-to-expire']) }}">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="card-icon mb-5 fs-1">
                                <i class="ph ph-hourglass"></i>
                            </div>
                            <div class="card-data">
                                <h1 class="">{{ $totalsoontoexpire }}</h1>
                                <p class="mb-0 fs-6">{{ __('dashboard.lbl_soon_to_expire') }}</p>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-md-4 col-sm-6">
                <a href="{{ route('backend.reviews.index') }}">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="card-icon mb-5 fs-1">
                                <i class="ph ph-code-block"></i>
                            </div>
                            <div class="card-data">
                                <h1 class="">{{ $totalreview }}</h1>
                                <p class="mb-0 fs-6">{{ __('dashboard.lbl_review') }}</p>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-md-4 col-sm-6">
                <a >
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="card-icon mb-5 fs-1">
                                <i class="ph ph-lockers"></i>
                            </div>
                            <div class="card-data">
                                <h1 class="">{{ $totalUsageFormatted }}</h1>
                                <p class="mb-0 fs-6">{{ __('dashboard.lbl_storage_full') }}</p>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-md-4 col-sm-6">
                    <a href="{{ route('backend.subscriptions.index') }}">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="card-icon mb-5 fs-1">
                                <i class="ph ph-money"></i>
                            </div>
                            <div class="card-data">
                                <h1 class="">{{ Currency::format( $total_revenue) }}</h1>
                                <p class="mb-0 fs-6">{{ __('dashboard.lbl_total_revenue') }}</p>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
                <!-- @if(isenablemodule('movie')==1)
                <div class="col-md-4 col-sm-6">
                <a href="{{ route('backend.movies.index') }}">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="card-icon mb-5 fs-1">
                                <i class="ph ph-film-strip"></i>
                            </div>
                            <div class="card-data">
                                <h1 class="">{{ $totalmovies }}</h1>
                                <p class="mb-0 fs-6">{{ __('dashboard.lbl_total_movies') }}</p>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
                @endif
                @if(isenablemodule('tvshow')==1)
                <div class="col-md-4 col-sm-6">
                <a href="{{ route('backend.tvshows.index') }}">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="card-icon mb-5 fs-1">
                                <i class="ph ph-television-simple"></i>
                            </div>
                            <div class="card-data">
                                <h1 class="">{{ $totaltvshow }}</h1>
                                <p class="mb-0 fs-6">{{ __('dashboard.lbl_total_shows') }}</p>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
                @endif
                @if(isenablemodule('video')==1)
                <div class="col-md-4 col-sm-6">
                <a href="{{ route('backend.videos.index') }}">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="card-icon mb-5 fs-1">
                                <i class="ph ph-video"></i>
                            </div>
                            <div class="card-data">
                                <h1 class="">{{ $totalvideo }}</h1>
                                <p class="mb-0 fs-6">{{ __('dashboard.lbl_total_videos') }}</p>
                            </div>
                        </div>
                    </div>
                </a>
                </div>
                @endif -->
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-stats">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.lbl_top_genres') }}</h3>
                </div>
                <div class="card-body">
                    <div id="chart-top-genres"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-stats card-block card-height">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <h3 class="card-title">{{ __('dashboard.lbl_tot_revenue') }}</h3>
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle total_revenue" type="button" id="dropdownTotalRevenue" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('dashboard.year') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-soft-primary sub-dropdown" aria-labelledby="dropdownTotalRevenue">
                            <li><a class="revenue-dropdown-item dropdown-item" data-type="Year">{{ __('dashboard.year') }}</a></li>
                            <li><a class="revenue-dropdown-item dropdown-item" data-type="Month">{{ __('dashboard.month') }}</a></li>
                            <li><a class="revenue-dropdown-item dropdown-item" data-type="Week">{{ __('dashboard.week') }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-top-revenue"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-stats card-block card-height">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <h3 class="card-title">{{ __('dashboard.new_subscribers') }}</h3>
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle total_subscribers" type="button" id="dropdownNewSubscribers" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('dashboard.year') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-soft-primary sub-dropdown" aria-labelledby="dropdownNewSubscribers">
                            <li><a class="subscribers-dropdown-item dropdown-item" data-type="Year">{{ __('dashboard.year') }}</a></li>
                            <li><a class="subscribers-dropdown-item dropdown-item" data-type="Month">{{ __('dashboard.month') }}</a></li>
                            <li><a class="subscribers-dropdown-item dropdown-item" data-type="Week">{{ __('dashboard.week') }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-new-subscription"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-block card-height">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <h3 class="card-title">{{ __('dashboard.lbl_most_watched') }}</h3>
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle most_watch" type="button" id="dropdownMostWatch" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('dashboard.year') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-soft-primary sub-dropdown" aria-labelledby="dropdownMostWatch">
                            <li><a class="mostwatch-dropdown-item dropdown-item" data-type="Year">{{ __('dashboard.year') }}</a></li>
                            <li><a class="mostwatch-dropdown-item dropdown-item" data-type="Month">{{ __('dashboard.month') }}</a></li>
                            <li><a class="mostwatch-dropdown-item dropdown-item" data-type="Week">{{ __('dashboard.week') }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-most-watch"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-stats card-block card-height">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <h3 class="card-title">{{ __('customer.reviews') }}</h3>
                    <a href="{{ route('backend.reviews.index') }}">{{ __('dashboard.view_all') }}</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <th>{{ __('dashboard.name') }}</th>
                                <th>{{ __('dashboard.date') }}</th>
                                <th>{{ __('dashboard.category') }}</th>
                                <th>{{ __('dashboard.rating') }}</th>
                            </thead>
                            <tbody>
                                @if($reviewData)
                                @foreach($reviewData as $review)
                                <tr>
                                    {{-- <td>{{ $review->created_at->format('d/m/Y') }}</td> --}}

                                    <td class="d-flex gap-3 align-items-center">
                                        <img src="{{ setBaseUrlWithFileName(optional($review->user)->file_url) ?? default_user_avatar() }}" alt="avatar" class="avatar avatar-40 rounded-pill">
                                        <div class="text-start">
                                            <h6 class="m-0">{{ optional($review->user)->first_name.' '.optional($review->user)->last_name  ?? default_user_name() }}</h6>
                                            <small>{{ optional($review->user)->email ?? '--' }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $review->created_at ? formatDate($review->created_at->format('Y-m-d')) : ''}}</td>
                                    <!-- <td>{{ optional($review->user)->first_name . ' ' . optional($review->user)->last_name }}</td> -->
                                    <td>{{ ucfirst(optional($review->entertainment)->type) }}</td>
                                    <td>
                                        <div class="d-flex gap-3 align-items-center">
                                            <!-- {{ $review->rating }} -->
                                            <div class="star-rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">
                                                    <i class="ph ph-fill ph-star"></i>
                                                    </span>
                                                @endfor
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5">{{__('messages.no_data_available')}}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="card card-block card-height">
                        <div class="card-header card-header-primary">
                            <h3 class="card-title">{{ __('dashboard.lbl_top_rated') }}</h3>
                        </div>
                        <div class="card-body p-0">
                            <div id="chart-top-rated"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6">
                    <div class="card card-block card-height">
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <h3 class="card-title">{{ __('dashboard.transaction_history') }}</h3>
                            <a href="{{ route('backend.subscriptions.index') }}">{{ __('dashboard.view_all') }}</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="text-primary">
                                        <th>{{ __('dashboard.name') }}</th>
                                        <th>{{ __('dashboard.date') }}</th>
                                        <th>{{ __('dashboard.plan') }}</th>
                                        <th>{{ __('dashboard.amount') }}</th>
                                        <th>{{ __('dashboard.duration') }}</th>
                                        <th>{{ __('dashboard.payment_method') }}</th>
                                    </thead>
                                    <tbody>

                                        @foreach($subscriptionData as $subscription)
                                        <tr>
                                            <td class="d-flex gap-3 align-items-center">
                                                <img src="{{ setBaseUrlWithFileName(optional($subscription->user)->file_url) ?? default_user_avatar() }}" alt="avatar" class="avatar avatar-40 rounded-pill">
                                                <div class="text-start">
                                                    <h6 class="m-0">{{ optional($subscription->user)->first_name .' '. optional($subscription->user)->last_name  ?? default_user_name() }}</h6>
                                                    <small>{{ optional($subscription->user)->email ?? '--' }}</small>
                                                </div>
                                            </td>
                                            <td>{{ optional($subscription->subscription_transaction)->created_at ? formatDate(optional($subscription->subscription_transaction)->created_at->format('Y-m-d')) : '--' }}</td>
                                            <td>{{ $subscription->name }}</td>
                                            <td>{{ Currency::format($subscription->amount) }}</td>
                                            <td>{{ $subscription->duration. ' ' . optional($subscription->plan)->duration }}</td>
                                            <td>{{ ucfirst(optional($subscription->subscription_transaction)->payment_type) }}</td>
                                        </tr>
                                        @endforeach
                                        @if($subscriptionData->isEmpty())
                                        <tr>
                                            <td colspan="5">{{__('messages.no_data_available')}}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@push('after-scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>

const formatCurrencyvalue = (value) => {
           if (window.currencyFormat !== undefined) {
             return window.currencyFormat(value)
           }
           return value
        }

    document.addEventListener('DOMContentLoaded', function() {
        var Base_url = "{{ url('/') }}";
        var url = Base_url + "/app/get_genre_chart_data";

        $.ajax({
            url: url,
            method: "GET",
            data: {},
            success: function(response) {
                if (document.querySelectorAll('#chart-top-genres').length) {
                    const chartData = response.data.chartData;
                    const category = response.data.category;
                    const options = {
                        series: chartData,
                        chart: {
                            height: 255,
                            type: 'donut',
                        },
                        stroke: {
                            width: 0,
                        },
                        colors: ['var(--bs-primary)', 'var(--bs-primary-600)', 'var(--bs-primary-700)', 'var(--bs-primary-800)', 'var(--bs-primary-400)'],
                        labels: category,
                        dataLabels: {
                            enabled: false,
                        },
                        legend: {
                            show: true,
                            position: 'bottom',
                            fontSize: '14px',
                            labels: {
                                colors: ['var(--bs-white)', 'var(--bs-white)', 'var(--bs-white)', 'var(--bs-white)', 'var(--bs-white)']
                            },
                        }

                    };

                    var chart = new ApexCharts(document.querySelector("#chart-top-genres"), options);
                    chart.render();
                }
            }
        });
    });

    revanue_chart('Year')

    var chart = null;
    let revenueInstance;

    function revanue_chart(type) {
    var Base_url = "{{ url('/') }}";
    var url = Base_url + "/app/get_revnue_chart_data/" + type;

    $("#revenue_loader").show();

    $.ajax({
        url: url,
        method: "GET",
        data: {},
        success: function(response) {
            $("#revenue_loader").hide();
            $(".total_revenue").text(type);

            if (document.querySelectorAll('#chart-top-revenue').length) {
                const monthlyTotals = response.data.chartData;
                const category = response.data.category;

                const options = {
                    series: [{
                        name: "Total Revenue",
                        data: monthlyTotals
                    }],
                    chart: {
                        height: 350,
                        type: 'line',
                        zoom: {
                            enabled: false
                        }
                    },
                    colors: ['#E50914'],
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                    },
                    grid: {
                        borderColor: '#404A51',
                        row: {
                            colors: ['#f3f3f3', 'transparent'],
                            opacity: 0
                        },
                    },
                    xaxis: {
                        categories: category
                    },
                    tooltip: {
                        theme: 'dark',
                        y: {
                            formatter: function(value) {
                                return formatCurrencyvalue(value); // Currency formatting
                            }
                        }
                    },
                };

                if (revenueInstance) {
                    revenueInstance.updateOptions(options);
                } else {
                    revenueInstance = new ApexCharts(document.querySelector("#chart-top-revenue"), options);
                    revenueInstance.render();
                }
            }
        }
    });
}

    $(document).on('click', '.revenue-dropdown-item', function() {
        var type = $(this).data('type');
        revanue_chart(type);
    });


    subscriber_chart('Year')
    let subscriberInstance;

    function subscriber_chart(type) {
        var Base_url = "{{ url('/') }}";
        var url = Base_url + "/app/get_subscriber_chart_data/" + type;

        $("#subscriber_loader").show();

        $.ajax({
            url: url,
            method: "GET",
            data: {},
            success: function(response) {
                $("#subscriber_loader").hide();
                $(".total_subscribers").text(type);
                if (document.querySelectorAll('#chart-new-subscription').length) {
                    const chartData = response.data.chartData;
                    const category = response.data.category;
                    const options = {
                        series: chartData,
                        chart: {
                            type: 'bar',
                            height: 350,
                            stacked: true,
                            toolbar: {
                                show: true
                            },
                            zoom: {
                                enabled: true
                            }
                        },
                        colors: ['#E50914', '#A31B22', '#70070C', '#5A0206'],
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                legend: {
                                    position: 'bottom',
                                    offsetX: -20,
                                    offsetY: 0
                                }
                            }
                        }],
                        grid: {
                            borderColor: '#404A51',
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '25%',
                                borderRadius: 3,
                                borderRadiusApplication: 'end', // 'around', 'end'
                                borderRadiusWhenStacked: 'last', // 'all', 'last'
                                dataLabels: {
                                    total: {
                                        enabled: true,
                                        style: {
                                            fontSize: '13px',
                                            fontWeight: 900,
                                            color: 'var(--bs-body-color)'
                                        }
                                    }
                                }
                            },
                        },
                        xaxis: {
                            // type: 'datetime',
                            categories: category
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            labels: {
                                colors: 'var(--bs-body-color)',
                            }
                        },
                        fill: {
                            opacity: 1
                        },
                        tooltip: {
                            theme: 'dark',
                        },
                    };

                    if (subscriberInstance) {
                        subscriberInstance.updateOptions(options);
                    } else {
                        subscriberInstance = new ApexCharts(document.querySelector("#chart-new-subscription"), options);
                        subscriberInstance.render();
                    }
                }
            }
        })
    };

    $(document).on('click', '.subscribers-dropdown-item', function() {
        var type = $(this).data('type');
        subscriber_chart(type);
    });


    document.addEventListener('DOMContentLoaded', function() {
    var Base_url = "{{ url('/') }}";
    var url = Base_url + "/app/get_toprated_chart_data";

    $.ajax({
        url: url,
        method: "GET",
        data: {},
        success: function(response) {
            if (document.querySelectorAll('#chart-top-rated').length) {
                const chartData = response.data.chartData;

                // Prepare series data and labels
                const series = chartData.map(item => item.data); // Extract data values
                const labels = chartData.map(item => item.name); // Extract names

                // Format the series for radialBar
                const formattedSeries = series.map(data => [data]); // Wrap each data value in an array

                const options = {
                    series: formattedSeries,
                    chart: {
                        height: 430,
                        type: 'radialBar',
                        events: {
                            dataPointSelection: function(event, chartContext, { dataPointIndex }) {
                                // Log the clicked data point
                                console.log('Clicked on segment:', labels[dataPointIndex], 'with value:', series[dataPointIndex]);
                            }
                        }
                    },
                    colors: ['var(--bs-primary-500)', 'var(--bs-primary-700)'],
                    labels: labels,
                    dataLabels: {
                        enabled: true,
                    },
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                size: "65%"
                            },
                            track: {
                                background: 'var(--bs-body-bg)',
                                strokeWidth: '100%',
                            },
                            dataLabels: {
                                name: {
                                    fontSize: '30px',
                                    color: 'var(--bs-heading-color)',
                                },
                                value: {
                                    fontSize: '16px',
                                    color: 'var(--bs-heading-color)',
                                    formatter: function (val) {
                                        return val;
                                    }
                                },
                                total: {
                                    show: true,
                                    color: 'var(--bs-heading-color)',
                                    fontSize: '22px',
                                    label: 'Total',
                                    formatter: function (w) {
                                        // Calculate total from series values
                                        let total = w.config.series.reduce((a, b) => a + b[0], 0); // sum up each entry's value
                                        return total;
                                    }
                                }
                            }
                        }
                    },
                    legend: {
                        show: true,
                        position: 'bottom',
                        fontSize: '14px',
                        labels: {
                            colors: ['var(--bs-white)', 'var(--bs-white)']
                        },
                    },
                    responsive: [{
                        breakpoint: 300,
                        options: {
                            chart: {
                                height: 150,
                            },
                        },
                    }]
                };

                // Create the chart instance
                var chart = new ApexCharts(document.querySelector("#chart-top-rated"), options);
                chart.render().then(() => {
                    // Attach click event listener to legend labels
                    const legendItems = document.querySelectorAll('#chart-top-rated .apexcharts-legend-series');

                    legendItems.forEach((item, index) => {
                        item.addEventListener('click', function() {
                            // Use toggleSeries to safely toggle visibility
                            chart.toggleSeries(labels[index]);
                        });
                    });
                });
            }
        }
    });
});




    mostwatch_chart('Year')
    let mostwatchInstance;

    function mostwatch_chart(type) {
        var Base_url = "{{ url('/') }}";
        var url = Base_url + "/app/get_mostwatch_chart_data/" + type;

        $("#mostwatch_loader").show();

        $.ajax({
            url: url,
            method: "GET",
            data: {},
            success: function(response) {
                $("#mostwatch_loader").hide();
                $(".most_watch").text(type);
                if (document.querySelectorAll('#chart-most-watch').length) {
                    const chartData = response.data.chartData;
                    const category = response.data.category;
                    const options = {
                        series: chartData,
                        chart: {
                            type: 'bar',
                            height: 350,
                            stacked: true,
                            toolbar: {
                                show: true
                            },
                            zoom: {
                                enabled: true
                            }
                        },
                        colors: ['#E50914', '#A31B22', '#70070C', '#5A0206'],
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                legend: {
                                    position: 'bottom',
                                    offsetX: -10,
                                    offsetY: 0,

                                }
                            }
                        }],
                        grid: {
                            borderColor: '#404A51',
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '25%',
                                borderRadius: 3,
                                borderRadiusApplication: 'end', // 'around', 'end'
                                borderRadiusWhenStacked: 'last', // 'all', 'last'
                                dataLabels: {
                                    total: {
                                        enabled: true,
                                        style: {
                                            fontSize: '13px',
                                            fontWeight: 900,
                                            color: 'var(--bs-body-color)'
                                        }
                                    }
                                }
                            },
                        },
                        xaxis: {
                            // type: 'datetime',
                            categories: category
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            labels: {
                                colors: 'var(--bs-body-color)',
                            },
                            markers: {
                                offsetX: -5
                            }
                        },
                        fill: {
                            opacity: 1
                        },
                        tooltip: {
                            theme: 'dark',
                        },
                    };

                    if (mostwatchInstance) {
                        mostwatchInstance.updateOptions(options);
                    } else {
                        mostwatchInstance = new ApexCharts(document.querySelector("#chart-most-watch"), options);
                        mostwatchInstance.render();
                    }
                }
            }
        })
    };

    $(document).on('click', '.mostwatch-dropdown-item', function() {
        var type = $(this).data('type');
        mostwatch_chart(type);
    });
</script>

@endpush
<style>
    .star-rating {
    display: flex;
}

.star {
        font-size: 1.2rem;
        color: var(--bs-border-color);
        /* Default color for empty stars */
        margin-right: 2px;
    }

    .star.filled {
        color: var(--bs-warning);
        /* Color for filled stars */
    }
</style>
