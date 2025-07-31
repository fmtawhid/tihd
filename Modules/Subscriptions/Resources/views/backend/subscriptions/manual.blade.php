@extends('backend.layouts.app')

@section('title')
    Manual Subscription Add
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Add Manual Subscription</h4>
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