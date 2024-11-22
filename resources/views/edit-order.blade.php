<!DOCTYPE html>
<html lang="en">
<body class="bg-gradient-login">
@include('common.sidebar')

<script>
    @if(session('success'))
    document.addEventListener('DOMContentLoaded', function () {
        const toastElement = document.createElement('div');
        toastElement.className = 'toast';
        toastElement.innerHTML = "{{ session('success') }}";
        document.body.appendChild(toastElement);
        $(toastElement).toast('show');
    });
    @endif
</script>

<!-- Add Order Content -->
<div class="container-login">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-12">
            <div class="card shadow-sm my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="login-form">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">@if ($uorderid) Edit Order @else Add New Order @endif </h1>
                                </div>

                                <!-- Validation Errors Display -->
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Order Form -->
                                <form id="addOrderForm" method="POST" action="{{ route('orders.update', ['id' => $uorderid->id]) }}">
                                    @csrf
                                    @method('PUT')

                                    <!-- Company Name Dropdown -->
                                    <div class="form-group">
                                        <label>Company Name</label>
                                        <select class="form-control" id="companyName" required name="company_name">
                                            <option disabled selected>Select Company</option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}" {{ (isset($uorderid) && $uorderid->company_name == $company->id) ? 'selected' : '' }}>
                                                    {{ $company->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Locations and Rate -->
                                    <div class="form-group">
                                        <label>From</label>
                                        <input type="text" class="form-control" name="from_location" required placeholder="Enter Start Location" value="{{ $uorderid->location_from }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Where</label>
                                        <input type="text" class="form-control" name="destination" required placeholder="Enter Destination" value="{{ $uorderid->location_where }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Total Rate</label>
                                        <input type="number" class="form-control" id="totalRate" name="total_rate" required placeholder="Enter Total Rate" oninput="updateRemaining()" value="{{ $uorderid->total_rate }}">
                                    </div>

                                    <!-- Payment Status Dropdown -->
                                    <div class="form-group">
                                        <label>Payment</label>
                                        <select class="form-control" id="paymentStatus" required name="payment_status">
                                            <option disabled selected>Select Payment Status</option>
                                            <option value="Done" {{ (isset($uorderid) && $uorderid->payment == 'Done') ? 'selected' : '' }}>Done</option>
                                            <option value="Pending" {{ (isset($uorderid) && $uorderid->payment == 'Pending') ? 'selected' : '' }}>Pending</option>
                                        </select>
                                    </div>

                                    <!-- Conditional Fields for Pending Payment -->
                                    <div id="pendingFields" style="display: none;">
                                        <div class="form-group">
                                            <label>Amount Paid</label>
                                            <input type="number" class="form-control" id="amountPaid" name="amount_paid" placeholder="Enter Amount Paid" oninput="updateRemaining()" value="{{ $uorderid->amount_paid }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Amount Remaining</label>
                                            <input type="number" class="form-control" id="amountRemaining" name="amount_remaining" placeholder="Amount Remaining" value="{{ $uorderid->amount_remaining }}" readonly>
                                        </div>
                                        <div id="error-message" class="text-danger" style="display: none;"></div>
                                    </div>

                                    <!-- Driver Details -->
                                    <div class="form-group">
                                        <label>Driver Name</label>
                                        <select class="form-control" id="driverName" required name="driver_name">
                                            <option disabled selected>Select Driver</option>
                                            @foreach($drivers as $driver)
                                                <option value="{{ $driver->id }}" {{ (isset($uorderid) && $uorderid->driver == $driver->id) ? 'selected' : '' }}>
                                                    {{ $driver->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Driver Trip</label>
                                        <input type="number" class="form-control" id="drivertrip" name="driver_trip" placeholder="Driver trip" value="{{ $uorderid->driver_trip }}">
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">Update Order</button>
                                    </div>
                                </form>

                                <!-- JavaScript -->
                                <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const paymentStatus = document.getElementById('paymentStatus');
                                const pendingFields = document.getElementById('pendingFields');
                                const totalRateField = document.getElementById('totalRate');
                                const amountPaidField = document.getElementById('amountPaid');
                                const amountRemainingField = document.getElementById('amountRemaining');

                                // Check initial payment status and display fields if needed
                                if (paymentStatus.value === 'Pending') {
                                    pendingFields.style.display = 'block';
                                } else {
                                    pendingFields.style.display = 'none';
                                    amountPaidField.value = totalRateField.value; // Set amount paid to total rate
                                    amountRemainingField.value = 0; // Set amount remaining to 0
                                }

                                // Event listener for changes in payment status
                                paymentStatus.addEventListener('change', function() {
                                    if (paymentStatus.value === 'Pending') {
                                        pendingFields.style.display = 'block';
                                        amountPaidField.value = ''; // Clear amount paid for fresh entry
                                        amountRemainingField.value = ''; // Clear amount remaining
                                    } else {
                                        pendingFields.style.display = 'none';
                                        amountPaidField.value = totalRateField.value; // Set amount paid to total rate
                                        amountRemainingField.value = 0; // Set amount remaining to 0
                                    }
                                });
                            });

                            function updateRemaining() {
                                const totalRate = parseFloat(document.getElementById('totalRate').value) || 0;
                                const amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;
                                const amountRemainingField = document.getElementById('amountRemaining');
                                const errorMessage = document.getElementById('error-message');

                                errorMessage.style.display = 'none';
                                errorMessage.textContent = '';

                                if (amountPaid > totalRate) {
                                    errorMessage.textContent = 'Error: Amount Paid cannot exceed Total Rate.';
                                    errorMessage.style.display = 'block';
                                    amountRemainingField.value = 0;
                                    return;
                                }

                                const amountRemaining = totalRate - amountPaid;
                                amountRemainingField.value = amountRemaining >= 0 ? amountRemaining : 0;
                            }
                        </script>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
