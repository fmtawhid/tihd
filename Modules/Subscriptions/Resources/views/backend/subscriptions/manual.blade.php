@extends('backend.layouts.app')

@section('title', 'Manual Subscription Add')

@section('content')
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
                        <select name="payment_type" class="form-control" required>
                            <option value="">-- Select Payment Type --</option>
                            <option value="bank">Bank</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Transaction ID</label>
                        <input type="text" name="transaction_id" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">Add Subscription</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Side: User Info -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-secondary text-white rounded-top-4">
                <h5 class="mb-0 pb-3">👤 User Profile & Subscriptions</h5>
            </div>
            <div class="card-body">
                <!-- Search User -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Search User</label>
                    <select id="profileSearchSelect" class="form-control"></select>
                </div>

                <!-- User Info Placeholder -->
                <div id="userInfo" class="text-muted">
                    🔍 Search a user to see profile details & subscriptions here.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    // Left form search - select user to add subscription for
    $('#userSearchSelect').select2({
        placeholder: 'Search by name, email, or phone',
        ajax: {
            url: '{{ route("backend.users.search") }}',
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data }),
            cache: true
        }
    });

    // Right side profile search - show user info and subscriptions
    $('#profileSearchSelect').select2({
        placeholder: 'Search by name, email, or phone',
        ajax: {
            url: '{{ route("backend.users.search") }}',
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data }),
            cache: true
        }
    });

    // On selecting a user in profile search, fetch and display user info & subscriptions
$('#profileSearchSelect').on('select2:select', function(e) {
    let userId = e.params.data.id;

    $.get('{{ url("app/backend/users/info") }}/' + userId, function(data) {
        if (data.error) {
            $('#userInfo').html('<p class="text-danger">User not found.</p>');
            return;
        }

        let subsHtml = '';
        if (data.subscriptions && data.subscriptions.length) {
            subsHtml = '<div class="list-group">';
            data.subscriptions.forEach(sub => {
                // তারিখ ফরম্যাট dd/mm/yy
                let start = new Date(sub.start_date);
                let end = new Date(sub.end_date);
                let today = new Date();

                let startFormatted = start.toLocaleDateString('en-GB'); // dd/mm/yy
                let endFormatted = end.toLocaleDateString('en-GB'); // dd/mm/yy

                // বাকি দিন বা আগে কতদিন হয়েছে
                let diffTime = end - today;
                let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                let expiryText = '';
                if (diffDays > 0) {
                    expiryText = `<span class="badge bg-success">Expires in ${diffDays} days</span>`;
                } else if (diffDays === 0) {
                    expiryText = `<span class="badge bg-warning text-dark">Expires Today</span>`;
                } else {
                    expiryText = `<span class="badge bg-danger">Expired ${Math.abs(diffDays)} days ago</span>`;
                }

                subsHtml += `
                    <div class="list-group-item mb-2 border rounded shadow-sm">
                        <div class="fw-bold text-primary mb-1">${sub.plan}</div>
                        <div>💰 Price: <strong>${sub.price}</strong></div>
                        <div>📅 From: ${startFormatted} → To: ${endFormatted}</div>
                        <div>📌 Status: <strong>${sub.status}</strong></div>
                        <div class="mt-1">${expiryText}</div>
                    </div>
                `;
            });
            subsHtml += '</div>';
        } else {
            subsHtml = '<p>No subscriptions found.</p>';
        }

        $('#userInfo').html(`
            <div class="p-3 border rounded shadow-sm bg-dark text-white">
                <p><strong>Name:</strong> ${data.name}</p>
                <p><strong>Email:</strong> ${data.email}</p>
                <p><strong>Phone:</strong> ${data.phone}</p>
                <h6 class="mt-3">📦 Subscriptions:</h6>
                ${subsHtml}
            </div>
        `);
    }).fail(() => {
        $('#userInfo').html('<p class="text-danger">Failed to load user data.</p>');
    });
});

});
</script>
@endsection
