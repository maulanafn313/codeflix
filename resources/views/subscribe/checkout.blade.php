@extends('layouts.subscribe')

@section('title', 'Payment Detail')
@section('page-title', 'Payment Detail')

@section('content')
    <div class="mt-4 text-white card bg-dark border-green">
        <div class="card-body">
            <div class="mb-3 row align-items-center">
                <div class="col-8">
                    <h5 class="mb-0">{{ $plan->title }} - {{ $plan->duration }} Hari</h5>
                </div>
                <div class="col-4 text-end">
                    <span class="fs-5">Rp.{{ number_format($plan->price, 0, ',', '.') }}</span>
                </div>
            </div>

            <hr class="border-green">

            <div class="mb-2 row">
                <div class="col-8">Subtotal</div>
                <div class="col-4 text-end">Rp.{{ number_format($plan->price, 0, ',', '.') }}</div>
            </div>

            <div class="mb-2 row">
                <div class="col-8">Ppn 12%</div>
                <div class="col-4 text-end">Rp.{{ number_format($plan->price * 0.12, 0, ',', '.') }}</div>
            </div>

            <hr class="border-green">

            <div class="mb-4 row">
                <div class="col-8">Total payment</div>
                <div class="col-4 text-end fw-bold">Rp.{{ number_format($plan->price * 1.1, 0, ',', '.') }}</div>
            </div>

            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label" for="terms">
                    By continuing the payment, you agree to our
                    <a href="#" class="text-info">Terms and Conditions</a> and
                    <a href="#" class="text-info">Privacy Policy</a>
                </label>
            </div>

            {{-- <form action="#" method="POST"> --}}
            {{-- @csrf --}}
            <input type="hidden" id="plan_id" name="plan_id" value="{{ $plan->id }}">
            <input type="hidden" id="total_payment" name="total_payment" value="{{ $plan->price * 1.1 }}">
            <button type="button" class="w-100 btn btn-green" id="pay-button">Continue</button>
            {{-- </form> --}}
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    {{-- <script>
        const payButton = document.querySelector('#pay-button');
        payButton.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the default form submission

            fetch('/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    plan_id: '{{ $plan->id }}',
                    total_payment: '{{ $plan->price * 1.1 }}', // Total payment including tax
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Redirect to Midtrans payment page
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                window.location.href = '/subscribe/success';
                                // Handle successful payment here
                            },
                            onPending: function(result) {
                                window.location.href = '/payment/pending';
                                // Handle pending payment here
                            },
                            onError: function(result) {
                                window.location.href = '/payment/error';
                                // Handle payment error here
                            },
                            onClose: function() {
                                alert('Payment popup closed without completing the payment.');
                                // Handle payment closed event here
                            }
                        });
                    } else {
                        alert('Payment initialization failed. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing your payment. Please try again.');
                });
            })
        })
    </script> --}}

    <script>
        // ...existing code...
        document.getElementById('pay-button').addEventListener('click', function(e) {
            e.preventDefault();

            fetch('/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        plan_id: document.getElementById('plan_id').value,
                        amount: parseInt(document.getElementById('total_payment').value)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                window.location.href = '/subscribe/success';
                            },
                            onPending: function(result) {
                                window.location.href = '/payment/pending';
                            },
                            onError: function(result) {
                                window.location.href = '/payment/error';
                            },
                            onClose: function() {
                                alert('Payment popup closed without completing the payment.');
                            }
                        });
                    } else {
                        alert(data.message || 'Payment initialization failed. Please try again.');
                    }
                })
                .catch(error => {
                    alert('An error occurred while processing your payment. Please try again.');
                });
        });
    </script>
@endsection
