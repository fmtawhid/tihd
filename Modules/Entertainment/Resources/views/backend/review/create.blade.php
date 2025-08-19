@extends('backend.layouts.app')

@section('title')
    {{ __('messages.create') }} {{ __($module_title) }}
@endsection

@section('content')
    <div class="card-main mb-5">
        <x-backend.section-header>
            <x-slot name="left">
                <h5 class="mb-0 text-white">{{ __('messages.create') }} {{ __($module_title) }}</h5>
            </x-slot>
        </x-backend.section-header>

        <form action="{{ route('backend.reviews.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <div class="card-body form-dark">
                {{-- User --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold text-light">{{ __('users.lbl_user') }}</label>
                    <select name="user_id" class="form-control select2 input-dark" required>
                        <option value="">Select User</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Entertainment --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold text-light">{{ __('entertainment.lbl_entertainment') }}</label>
                    <select name="entertainment_id" class="form-control select2 input-dark" required>
                        <option value="">Select Entertainment</option>
                        @foreach ($entertainments as $entertainment)
                            <option value="{{ $entertainment->id }}">
                                {{ $entertainment->name }} ({{ ucfirst($entertainment->type) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Rating --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold text-light">{{ __('review.lbl_rating') }}</label>
                    <select name="rating" class="form-control input-dark" required>
                        <option value="">Select Ratings</option>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }} ⭐</option>
                        @endfor
                    </select>
                </div>

                {{-- Review --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold text-light">{{ __('review.lbl_review') }}</label>
                    <textarea name="review" class="form-control input-dark" rows="4"
                        placeholder="Enter Your Review" required></textarea>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end form-dark">
                <a href="{{ route('backend.reviews.index') }}" class="btn btn-warning me-2">
                    <i class="fa fa-arrow-left"></i> {{ __('messages.back') }}
                </a>
                <button type="submit" class="btn btn-secondary">
                    <i class="fa fa-save"></i> {{ __('messages.save') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('after-styles')
<style>
    /* Card Dark Background */
    .form-dark {
        background-color: #121D24;
        border-radius: 8px;
        padding: 20px;
    }

    /* Input Dark Mode */
    .input-dark {
        background-color: #111 !important;
        color: #fff !important;
        border: 1px solid #444;
    }

    .input-dark:focus {
        background-color: #222 !important;
        border-color: #666;
        box-shadow: none;
        color: #fff !important;
    }

    /* Select2 Dark Fix */
    .select2-container--default .select2-selection--single {
        background-color: #111 !important;
        border: 1px solid #444 !important;
        color: #fff !important;
        height: 38px;
    }

    .select2-container--default .select2-selection__rendered {
        color: #fff !important;
        line-height: 38px;
    }

    .select2-container--default .select2-selection__arrow b {
        border-color: #fff transparent transparent transparent !important;
    }

    /* Label color */
    label.form-label {
        color: #ddd !important;
    }


   
</style>
@endpush

@push('after-scripts')
<script>
    $(document).ready(function () {
        $('.select2').select2({
            width: '100%'
        });
    });
</script>
@endpush
