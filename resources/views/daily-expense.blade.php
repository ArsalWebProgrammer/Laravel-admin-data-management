@include('common.sidebar')
<div class="container-fluid" id="container-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daily Expenses</h6>
                    <a href="{{ route('expenses.index') }}" class="btn btn-primary">Add Expense</a>
                </div>
                <div class="table-responsive p-3">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Driver</th>
                                <th>Amount</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($expenses as $expense)
                            @if($expense->note)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($expense->created_at)->format('Y-m-d') }}</td>
                                    <td>{{ $expense->expense_type === 'driver' ? 'Driver' : 'General' }}</td>
                                    <td>{{ $expense->driver ? $expense->driver->name : '-' }}</td>
                                    <td>{{ $expense->expense_amount }} <strong>SAR</strong></td>
                                    <td>{{ $expense->note }}</td>
                                    <td>
                                    <form action="{{ route('expenses.destroy', ['id' => $expense->id]) }} " method="POST" onsubmit="return confirm('Are you sure you want to delete this expense?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('common.footerjqr')
<script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>