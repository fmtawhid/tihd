<div class="section-spacing-bottom">
    <div class="container">
        <a href="{{ route('subscriptionPlan') }}" class="text-decoration-none text-white flex-none"><i
                class="ph ph-caret-left"></i><span
                class="font-size-18 fw-medium">{{ __('frontend.back_to_subscription_plan') }}</span></a>
        <div class="mt-5">
            <div class="row">
                <div class="col-lg-3">

                    <form id="plan-form">
                        @foreach ($plans as $plan)
                            <div class="col-12 mb-4">
                                <label class="form-check stripe-payment-form p-4 position-relative rounded"
                                    for="{{ strtolower($plan->name) }}">
                                    @if ($plan->discount > 0)
                                        <input type="radio" id="{{ strtolower($plan->id) }}" name="plan_name"
                                            value="{{ $plan->id }}" data-amount="{{ $plan->total_price }}"
                                            class="form-check-input payment-radio-btn">
                                    @else
                                        <input type="radio" id="{{ strtolower($plan->id) }}" name="plan_name"
                                            value="{{ $plan->id }}" data-amount="{{ $plan->price }}"
                                            class="form-check-input payment-radio-btn">
                                    @endif
                                    <span class="form-check-label">
                                        <span class="text-uppercase fw-medium d-block mb-2">{{ $plan->name }}</span>
                                        @if ($plan->discount > 0)
                                            <span class="h4"> {{ Currency::format($plan->total_price) }} <del>
                                                    {{ Currency::format($plan->price) }}</del><span
                                                    class="font-size-14 text-body">/ {{ $plan->duration_value }}
                                                    {{ Str::plural('month', $plan->duration_value) }}</span></span>
                                        @else
                                            <span class="h4"> {{ Currency::format($plan->price) }} <span
                                                    class="font-size-14 text-body">/ {{ $plan->duration_value }}
                                                    {{ Str::plural('month', $plan->duration_value) }}</span></span>
                                        @endif
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </form>
                </div>
                <div class="col-lg-9 mt-lg-0 mt-5">
                    <form action="{{ route('process-payment') }}" method="POST" id="payment-form">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" id="selected-plan-id" name="plan_id">
                            <input type="hidden" id="selected-price" name="price">
                            <label class="form-label"
                                for="payment-method">{{ __('frontend.choose_payment_method') }}:</label>
                            <select id="payment-method" name="payment_method" class="form-select">

                                <option value="sslcommarz">sslcommarz</option>

                            </select>
                        </div>


                        <div class="mt-4">
                            <div class="payment-detail rounded">
                                <h6 class="font-size-18">{{ __('frontend.payment_details') }}</h6>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>

                                            <tr>
                                                <td>{{ __('frontend.price') }}</td>
                                                <td>
                                                    <h6 class="font-size-18 text-end mb-0" id="price"></h6>
                                                </td>
                                            </tr>

                                            <tr id="discount_class" class="d-none">
                                                <td class="d-flex gap-2">Discount <h6 id="discount_data"></h6>
                                                </td>
                                                <td>
                                                    <h6 class="font-size-18 text-end mb-0" id="discount"></h6>
                                                </td>
                                            </tr>

                                            <tr id="subtotal_class" class="d-none">
                                                <td>{{ __('frontend.subtotal') }}</td>
                                                <td>
                                                    <h6 class="font-size-18 text-end mb-0" id="subtotal"></h6>
                                                </td>
                                            </tr>
                                            <tr id="tax_tr">
                                                <td>
                                                    <p>{{ __('frontend.tax') }}</p>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center justify-content-end gap-2">
                                                        <span><i class="ph ph-info" aria-hidden="true"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#appliedTax"></i></span>

                                                        <h6 class="font-size-18 text-end text-primary mb-0"
                                                            id="tax"></h6>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td>{{ __('frontend.total') }}</td>
                                                <td>
                                                    <h6 class="font-size-18 text-end mb-0" id="total"></h6>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="mt-4">
                                        <div class="d-flex justify-content-between gap-3">
                                            <h6>{{ __('frontend.total_payment') }}</h6>
                                            <div class="d-flex justify-content-center align-items-center gap-3">
                                                <h5 class="mb-0" id="total-payment"></h5>
                                                <small><del id="old-price"></del></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="d-flex justify-content-end">
                                    <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <i class="ph ph-lock-key text-primary"></i>
                                            <p class="mb-0">{{ __('frontend.payment_secure') }}</p>
                                        </div>
                                        <button type="submit"
                                            class="btn btn-primary">{{ __('frontend.proceed_payment') }}</button>


                                        <div class="modal fade" id="appliedTax" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <div class="ph-circle-check text-primary font-size-140"></div>
                                                        <h5 class="font-size-28 mb-4">Applied Taxes</h5>

                                                        <div id="applied_tax">


                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center d-none">
                                            <div class="ph-circle-check text-primary font-size-140"></div>
                                            <h5 class="font-size-28 mb-4">{{ __('frontend.thanks_for_payment') }}</h5>
                                            <p>{{ __('frontend.payment_success') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">

                            </div>
                        </div>
                </div>
            </div>
        </div>
        </form>
        <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center">
                    <div class="modal-header justify-content-center">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="errorModalMessage"></p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            function formatCurrencyvalue(value) {
                if (window.currencyFormat !== undefined) {
                    return window.currencyFormat(value)
                }
                return value
            }

            $(document).ready(function() {
                @if (session('error'))
                    $('#errorModalMessage').text('{{ session('error') }}');
                    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorModal.show();
                @endif
                const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
                var selectedPlanId = @json($planId); // Injected from the backend
                if (selectedPlanId) {
                    $('input[type="radio"][value="' + selectedPlanId + '"]').prop('checked', true);
                    $('#selected-plan-id').val(selectedPlanId);
                    $('#selected-price').val($('input[type="radio"][value="' + selectedPlanId + '"]').data('amount'));
                    updatePaymentDetails(selectedPlanId);
                }
                $('.payment-radio-btn').on('change', function() {
                    var selectedPrice = $(this).data('amount');
                    var selectedPlanId = $(this).val();
                    $('#selected-price').val(selectedPrice);
                    $('#selected-plan-id').val(selectedPlanId);
                    updatePaymentDetails(selectedPlanId);
                });


                function updatePaymentDetails(planId) {

                    $('#discount_class').addClass('d-none');
                    $('#subtotal_class').addClass('d-none');

                    $.ajax({
                        url: `${baseUrl}/get-payment-details`,
                        method: 'POST',
                        data: {
                            plan_id: planId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#selected-price').val(response.subtotal + response.tax);
                            $('#price').text(formatCurrencyvalue(response.price));
                            $('#tax').text('+' + formatCurrencyvalue(response.tax));
                            $('#total').text(formatCurrencyvalue(response.total));
                            $('#total-payment').text(formatCurrencyvalue(response.total));

                            if (response.discount_amount > 0) {

                                $('#discount_class').removeClass('d-none');
                                $('#subtotal_class').removeClass('d-none');

                                $('#discount_data').text('(' + response.discount + '%)');

                                $('#discount').text('-' + formatCurrencyvalue(response.discount_amount));
                                $('#subtotal').text(formatCurrencyvalue(response.subtotal));

                            }
                            createTaxTable(response.tax_array);

                            if (response.tax === 0) {
                                $('#tax_tr').addClass('d-none');
                            } else {
                                $('#tax_tr').removeClass('d-none');
                            }
                        },
                        error: function(xhr) {
                            $('#errorModalMessage').text(
                                'An error occurred while fetching payment details.');
                            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                            errorModal.show();
                        }
                    });
                }

                function createTaxTable(taxes) {
                    let tableHtml = '<table class="table"><tbody>';
                    taxes.forEach(function(tax) {
                        tableHtml += '<tr>';
                        if (tax.type.toLowerCase() === 'percentage') {
                            tableHtml += `<td>${tax.name} (${tax.value}%)</td>`;
                        } else {
                            tableHtml += `<td>${tax.name} ($${tax.value})</td>`;
                        }
                        tableHtml += `<td>$${tax.tax_amount}</td>`;
                        tableHtml += '</tr>';
                    });
                    tableHtml += '</tbody></table>';
                    $('#applied_tax').html(tableHtml);
                }

                $('#payment-form').on('submit', function(e) {
                    e.preventDefault(); // Prevent default form submission
                    const paymentMethod = $('#payment-method').val();
                    if (!paymentMethod) {
                        $('#errorModalMessage').text('Please select a payment method before proceeding.');
                        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                        errorModal.show();
                        return; // Exit the function
                    }
                    const formData = $(this).serialize();
                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            }
                        },
                        error: function(xhr) {
                            const errorResponse = xhr.responseJSON || {};
                            const errorMessage = errorResponse.error ||
                                'An error occurred. Please try another payment method.';
                            $('#errorModalMessage').text(errorMessage);
                            var errorModal = new bootstrap.Modal(document.getElementById(
                                'errorModal'));
                            errorModal.show();
                        }
                    });

                });
            });
        </script>
