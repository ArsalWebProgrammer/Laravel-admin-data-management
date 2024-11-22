@include('common.sidebar')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container-fluid" id="container-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                </div>
                <div class="table-responsive p-3">
                    <div class="container">
                        <h1>Order Details</h1>
                        
                        <table class="table table-bordered">
                            <tr>
                                <th>Order ID</th>
                                <td>{{ $order->id }}</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <th>Company Name</th>
                                <td>{{ $order->client ? $order->client->company_name : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>From</th>
                                <td>{{ $order->location_from }}</td>
                            </tr>
                            <tr>
                                <th>To</th>
                                <td>{{ $order->location_where }}</td>
                            </tr>
                            <tr>
                                <th>Driver</th>
                                <td>{{ $order->driver1 ? $order->driver1->name : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Driver Trip</th>
                                <td>{{ $order->driver_trip }}</td>
                            </tr>
                            <tr>
                                <th>Payment Status</th>
                                <td>{{ $order->payment }}</td>
                            </tr>
                            <tr>
                                <th>Total Amount</th>
                                <td>{{ $order->total_rate }}</td>
                            </tr>
                            <tr>
                                <th>Amount Paid</th>
                                <td>{{ $order->amount_paid }}</td>
                            </tr>
                            <tr>
                                <th>Amount Remaining</th>
                                <td>{{ $order->amount_remaining }}</td>
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <td>{{ $order->user ? $order->user->name : 'N/A' }}</td>
                            </tr>
                        </table>
                        <br>
                        <!-- Buttons to be hidden when printing -->
                        <div class="no-print">
                            <a href="{{ route('all-orders') }}" class="btn btn-primary">Back to Orders</a>
                            <a href="{{ route('edit-order', $order->id) }}" class="btn btn-primary">Edit Order</a>
                            <button class="btn btn-info" style="float: right" onclick="window.print()">Print</button>

                            <!-- Delete Button -->
                            <form action="{{ route('delete-order', $order->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="float: right; margin-right: 10px;">Delete Order</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('common.footerjqr')

<!-- CSS to hide elements when printing -->
<style>
    @media print {
        .no-print {
            display: none;
        }
    }
</style>
