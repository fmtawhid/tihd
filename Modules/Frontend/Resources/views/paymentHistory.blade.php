@extends('frontend::layouts.master')
@section('content')
<style>
    .table-responsive {
        -webkit-overflow-scrolling: touch !important;
        overflow-x: hidden !important;  /* Hide the default horizontal scrollbar */
        position: relative !important;
        width: 100% !important;
        display: block !important;  /* Ensures the element is treated as a block element */
    }

    /* Custom Scroll buttons */
    .scroll-buttons {
        /* position: absolute; */
        bottom: 10px;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        padding: 0 10px;
        z-index: 100;
        display: none;
        align-items: center;
        gap: 20px;
    }
    @media (max-width: 1070.98px) {
        .scroll-buttons{
            display: flex;
            /* display: block; */
            text-align: center;
        }
    }

    .scroll-btn {
        background-color: #c42629;
        color: white;
        border: none;
        padding: 10px;
        font-size: 14px;
        cursor: pointer;
    }

    .table-responsive::-webkit-scrollbar {
        height: 8px !important;  /* Customize scrollbar height */
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background-color: #888 !important;
        border-radius: 4px !important;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background-color: #555 !important;
    }
</style>

<div class="page-title">
    <h4 class="m-0 text-center">{{__('frontend.membership')}}</h4>
</div>
<div class="section-spacing">
    <div class="container">
        <div class="upgrade-plan d-flex flex-wrap gap-3 align-items-center justify-content-between rounded p-4 bg-warning-subtle border border-warning">
            <div class="d-flex justify-content-center align-items-center gap-4">
                <i class="ph ph-crown text-warning"></i>
                <div>
                    @if($activeSubscriptions)
                        <h6 class="super-plan">{{ $activeSubscriptions->name }}</h6>
                        <p class="mb-0 text-body">{{__('frontend.expiring_on')}} {{ \Carbon\Carbon::parse($activeSubscriptions->end_date)->format('d F, Y') }}</p>
                    @else
                        <h6 class="super-plan">{{__('frontend.no_active_plan')}}</h6>
                        <p class="mb-0 text-body">{{__('frontend.not_active_subscription')}}</p>
                    @endif
                </div>
            </div>
            <div class="d-flex gap-3">
                @if($activeSubscriptions)
                    <a href="{{ route('subscriptionPlan') }}" class="btn btn-light">{{__('frontend.upgrade')}}</a>
                @else
                    <a href="{{ route('subscriptionPlan') }}" class="btn btn-light">{{__('frontend.subscribe')}}</a>
                @endif
            </div>
        </div>

        <div class="section-spacing-bottom px-0">
            <h5 class="main-title text-capitalize mb-2">{{__('frontend.payment_history')}}</h5>
            <div class="table-responsive">
                <table class="table payment-history table-borderless">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-white">{{__('frontend.date')}}</th>
                            <th class="text-white">{{__('frontend.plan')}}</th>
                            <th class="text-white">{{__('dashboard.duration')}}</th>
                            <th class="text-white">{{__('frontend.expiry_date')}}</th>
                            <th class="text-white">{{__('frontend.amount')}}</th>
                            <th class="text-white">{{__('frontend.tax')}}</th>
                            <th class="text-white">{{__('frontend.total')}}</th>
                            <th class="text-white">{{__('frontend.payment_method')}}</th>
                            <th class="text-white">{{__('frontend.status')}}</th>
                            <th class="text-white">{{__('frontend.invoice')}}</th>
                        </tr>
                    </thead>
                    <tbody class="payment-info">
                        @if($subscriptions->isEmpty())
                            <tr>
                                <td colspan="10" class="text-center text-white fw-bold">
                                    {{ __('frontend.subscription_history_not_found') }}
                                </td>
                            </tr>
                        @else
                            @foreach($subscriptions as $subscription)
                                <tr>
                                    <td class="fw-bold text-white">{{ \Carbon\Carbon::parse($subscription->created_at)->format('d/m/Y') }}</td>
                                    <td class="fw-bold text-white">{{ $subscription->name }}</td>
                                    <td class="fw-bold text-white">{{ $subscription->duration }} {{ $subscription->type }}</td>
                                    <td class="fw-bold text-white">{{ \Carbon\Carbon::parse($subscription->end_date)->format('d/m/Y') }}</td>
                                    <td class="fw-bold text-white">${{ number_format($subscription->amount, 2) }}</td>
                                    <td class="fw-bold text-white">${{ number_format($subscription->tax_amount, 2) }}</td>
                                    <td class="fw-bold text-white">${{ number_format($subscription->total_amount, 2) }}</td>
                                    <td class="fw-bold text-white">{{ ucfirst($subscription->subscription_transaction->payment_type ?? '-') }}</td>
                                    <td class="fw-bold text-white">{{ ucfirst($subscription->status ?? '-') }}</td>
                                    <td class="fw-bold"><a href="{{route('downloadinvoice', ['id' => $subscription->id])}}">{{__('frontend.download_invoice')}}</a></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Custom Scroll Buttons -->
            <div class="scroll-buttons">
                <button class="scroll-btn" id="scrollLeft"><i class="ph ph-arrow-left"></i> </button>
                <button class="scroll-btn" id="scrollRight"><i class="ph ph-arrow-right"></i></button>
            </div>
        </div>
    </div>
</div>

<script>
    // Scroll buttons functionality
    document.getElementById('scrollLeft').addEventListener('click', function() {
        let tableWrapper = document.querySelector('.table-responsive');
        tableWrapper.scrollBy({
            left: -200, // Scroll left by 200px
            behavior: 'smooth'
        });
    });

    document.getElementById('scrollRight').addEventListener('click', function() {
        let tableWrapper = document.querySelector('.table-responsive');
        tableWrapper.scrollBy({
            left: 200, // Scroll right by 200px
            behavior: 'smooth'
        });
    });
</script>

@endsection
