@include('common.sidebar')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container-fluid" id="container-wrapper">
    <div class="row">
         <!-- DataTable with Hover -->
         <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">All Orders</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                            <tr>
                                <th>Order Id</th>
                                <th>Date</th>
                                <th>Company Name</th>
                                <th>From</th>
                                <th>Location</th>
                                <th>Driver</th>
                                <th>Payment Status</th>
                                <th>Total Amount</th>
                                <th>Amount Paid</th>
                                <th>Amount Remaining</th>
                                <th>Order Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($orders as $order)
                    <tr onclick="window.location='{{ route('ordershow', ['id' => $order->id]) }}'" style="cursor:pointer;">
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        <td>{{ $order->client ? $order->client->company_name : 'N/A' }}</td>
                        <td>{{ $order->location_from }}</td>
                        <td>{{ $order->location_where }}</td>
                        <td>{{ $order->driver1 ? $order->driver1->name : 'N/A' }}</td>
                        <td>{{ $order->payment }}</td>
                        <td>{{ $order->total_rate }}</td>
                        <td>{{ $order->amount_paid }}</td>
                        <td>{{ $order->amount_remaining }}</td>
                        <td>{{ $order->user ? $order->user->name : 'N/A' }}</td>
                    </tr>
                @endforeach

                    </table>
                </div>
              </div>
            </div>
    </div>
</div>



@include('common.footerjqr')

  <!-- Page level custom scripts -->

<script>
    $(document).ready(function() {
        $('#dataTableHover').DataTable({
            "ordering": true,
            "order": [[0, "desc"]], // Sort by Date (4th column) in descending order by default
            "paging": true,
            "lengthMenu": [5, 10, 25, 50, 100], // Control page length options
            "language": {
                "search": "Filter records:"
            }
        });
    });
</script>

</body>
</html>