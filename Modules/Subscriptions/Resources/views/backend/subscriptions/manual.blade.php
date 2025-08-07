@extends('backend.layouts.app')

@section('title')
    Manual Subscription Add
@endsection

@section('content')
<<<<<<< Updated upstream
<div class="card">
    <div class="card-header">
        <h4>Add Manual Subscription</h4>
=======
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
  .select2-selection__rendered {
    color: white !important;
  }

  #userSearchSelect.select2-container--default.select2-container--open .select2-selection--single {
    color: white !important;
  }
     /* Fix dropdown width for select boxes */
    .form-select {
        width: 100% !important;
    }

    /* Bootstrap dropdown menu same width as parent */
    select.form-select {
        max-width: 100%;
    }

    /* If you use select2 for these in future, also force its width */
    .select2-container {
        width: 100% !important;
    }
    .select2-dropdown {
        background-color: #343a40 !important; /* Dark background */
    }
</style> 
<div class="row g-4">
    <!-- Left Side: Add Subscription Form -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-primary text-white rounded-top-4">
                <h5 class="mb-0 pb-3">➕ Add Manual Subscription</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('backend.subscriptions.manual') }}" method="POST">
                    @csrf
                    <!-- User Search -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Search User</label>
                        <select id="userSearchSelect" name="user_id" class="form-control" required style="color: white;"></select>
                    </div>

                    <!-- Plan -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Plan</label>
                        <select name="plan_id" class="form-select form-control" required>
                            <option value="">Select Plan</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} ({{ $plan->price }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Amount</label>
                        <input type="number" name="amount" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Payment Status</label>
                        <select name="payment_status" class="form-select form-control" required>
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Payment Type</label>
                        <input type="text" name="payment_type" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Transaction ID</label>
                        <input type="text" name="transaction_id" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">Add Subscription</button>
                </form>
            </div>
        </div>
>>>>>>> Stashed changes
    </div>
    <div class="card-body">
        <form action="{{ route('backend.subscriptions.manual') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>User</label>
                <select name="user_id" class="form-control" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->full_name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Plan</label>
                <select name="plan_id" class="form-control" required>
                    <option value="">Select Plan</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }} ({{ $plan->price }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Amount</label>
                <input type="number" name="amount" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Payment Status</label>
                <select name="payment_status" class="form-control" required>
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Payment Type</label>
                <input type="text" name="payment_type" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Transaction ID</label>
                <input type="text" name="transaction_id" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Add Subscription</button>
        </form>
    </div>
</div>
@endsection