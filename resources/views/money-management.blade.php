@include('common.sidebar')

<div class="container-fluid" id="container-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="table-responsive p-3">
                    <div class="container">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Money Management Records</h6>
                        <a href="{{ route('salary-management') }}" class="btn btn-primary mb-3">Add Salary</a>
</div>
                        <!-- DataTable with Hover -->
                        <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>User Type</th>
                                    <th>Salary</th>
                                    <th>Actions</th> <!-- New column for profile link -->
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($records as $record)
    <tr>
        <td>{{ $record->user_name }}</td> <!-- This might need to be 'name' or another column -->
        <td>{{ $record->user_type }}</td>
        <td>SAR{{ number_format($record->salary, 2) }}</td>
        <td>
            <a href="{{ route('user.profile', ['id' => $record->user_id]) }}" class="btn btn-sm btn-info">
                View Profile
            </a>
        </td>
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

@include('common.footerjqr')
<script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>