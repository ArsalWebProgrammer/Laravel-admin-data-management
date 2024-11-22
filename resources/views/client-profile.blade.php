@include('common.sidebar')

<div class="container-fluid" id="container-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Client Profile - {{ $client->name }}</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Print Button -->
                    <div class="mb-3 text-right" id="printButton">
                        <a href="javascript:window.print()" class="btn btn-outline-primary">
                            <i class="fa fa-print"></i> Print Profile
                        </a>
                    </div>

                    <div class="row">
                        <!-- Company Information Section -->
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-header text-center bg-primary text-white">
                                    <h6 class="m-0">Company Information</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li><strong>Company Name:</strong> {{ $companyName }}</li>
                                        <li><strong>Company Phone:</strong> {{ $companyPhone }}</li>
                                        <li><strong>Company ID:</strong> {{ $companyId }}</li>
                                        <li><strong>Total Orders:</strong> {{ $totalOrders }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Client Information Section -->
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-header text-center bg-info text-white">
                                    <h6 class="m-0">Client Information</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li><strong>Client Name:</strong> {{ $client->name }}</li>
                                        <li><strong>Email:</strong> {{ $client->email }}</li>
                                        <li><strong>Phone:</strong> {{ $client->phone }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Remaining Balances Section -->
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-header text-center bg-warning text-white">
                                    <h6 class="m-0">Remaining Balances</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li><strong>Remaining Balance This Month:</strong> {{ number_format($remainingThisMonth, 2) }}</li>
                                        <li><strong>Remaining Balance Last Month:</strong> {{ number_format($remainingLastMonth, 2) }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table for Remaining Amounts -->
                    <div class="table-responsive mt-4">
                        <div class="card shadow-sm">
                            <div class="card-header text-center bg-success text-white">
                                <h6 class="m-0">Remaining Amounts per Month</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Remaining Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($remainingAmounts as $month => $remainingAmount)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($month)->format('F Y') }}</td>
                                                <td>{{ number_format($remainingAmount, 2) }}</td>
                                            </tr>
                                        @endforeach
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

@include('common.footerjqr')

<script>
    $(document).ready(function () {
        $('#dataTable').DataTable();
        $('#dataTableHover').DataTable();
    });
</script>
<style>
    @media print {
        #printButton {
            display: none;
        }
    }
</style>