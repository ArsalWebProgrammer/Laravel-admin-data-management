<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Order</title>
    <link rel="stylesheet" href="path/to/your/bootstrap.css"> <!-- Include your Bootstrap CSS -->
</head>
<body class="bg-gradient-login">
    @include('common.sidebar')

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
                                        <h1 class="h4 text-gray-900 mb-4">Add New Order</h1>
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

                                    <form id="addOrderForm" method="POST" action="{{ route('addOrder') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label>Company Name</label>
                                            <select class="form-control" name="company_name" required>
                                                <option disabled selected value="">Select Company</option>
                                                @foreach($companies as $company)
                                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>From</label>
                                            <input type="text" class="form-control" name="from_location" required placeholder="Enter Start Location">
                                        </div>

                                        <div class="form-group">
                                            <label>Where</label>
                                            <input type="text" class="form-control" name="destination" required placeholder="Enter Destination">
                                        </div>

                                        <div class="form-group">
                                            <label>Total Rate</label>
                                            <input type="number" class="form-control" id="totalRate" name="total_rate" required placeholder="Enter Total Rate" oninput="updateRemaining()">
                                        </div>

                                        <div class="form-group">
                                            <label>Payment</label>
                                            <select class="form-control" id="paymentStatus" required name="payment_status">
                                                <option disabled selected value="">Select Payment Status</option>
                                                <option value="Done">Done</option>
                                                <option value="Pending">Pending</option>
                                            </select>
                                        </div>

                                        <div id="pendingFields" style="display: none;">
                                            <div class="form-group">
                                                <label>Amount Paid</label>
                                                <input type="number" class="form-control" id="amountPaid" name="amount_paid" placeholder="Enter Amount Paid" oninput="updateRemaining()" value="0">
                                            </div>
                                            <div class="form-group">
                                                <label>Amount Remaining</label>
                                                <input type="number" class="form-control" id="amountRemaining" name="amount_remaining" placeholder="Amount Remaining" readonly>
                                            </div>
                                            <div id="error-message" class="text-danger" style="display: none;"></div>
                                        </div>

                                        <div class="form-group">
                                            <label>Driver</label>
                                            <select class="form-control" name="driver" required>
                                                <option disabled selected value="">Select Driver</option>
                                                @foreach($drivers as $driver)
                                                    <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Driver Trip</label>
                                            <input type="number" class="form-control" id="drivertrip" name="driver_trip" placeholder="Driver Trip">
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block">Add Order</button>
                                        </div>
                                    </form>

                                    @if(session('success'))
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                alert("{{ session('success') }}");
                                            });
                                        </script>
                                    @endif

                                    <!-- JavaScript -->
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const paymentStatus = document.getElementById('paymentStatus');
                                            const pendingFields = document.getElementById('pendingFields');
                                            const totalRateField = document.getElementById('totalRate');
                                            const amountPaidField = document.getElementById('amountPaid');
                                            const amountRemainingField = document.getElementById('amountRemaining');
                                            const errorMessage = document.getElementById('error-message');

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

    <script src="path/to/your/bootstrap.bundle.js"></script> <!-- Include your Bootstrap JS -->
</body>
</html>
