<div> 
    @include('common.sidebar')  
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @include('common.footerjqr')
    <!-- Filter Dropdown -->

    <!-- Row -->
    <div class="container-fluid" id="container-wrapper">
    <div class="row">
    <div class="form-group col-lg-3">
            <form method="GET" action="{{ route('user.index') }}">
                <label for="userTypeFilter">Filter by User Type:</label>
                <select id="userTypeFilter" name="user_type" class="form-control" onchange="this.form.submit();">
                    <option value="">All Users</option>
                    <option value="Admin" {{ request('user_type') == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Driver" {{ request('user_type') == 'Driver' ? 'selected' : '' }}>Driver</option>
                    <option value="Client" {{ request('user_type') == 'Client' ? 'selected' : '' }}>Client</option>
                </select>
            </form>
        </div>
       
        <!-- Datatables -->
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Users List</h6>
                     <a href="{{ route('register') }}" class="btn btn-primary" style="height: 43px">Add New User</a>
                </div>
                <div class="table-responsive p-3">
                    <table class="table align-items-center table-flush" id="dataTable">
                        <thead class="thead-light">
                            <tr> 
                                <th>id</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>User Type</th>
                                <th class="driver-column">Driver Iqama</th> <!-- Column for Driver Iqama -->
                                <th class="client-column">Company Name</th> <!-- Fixed column for Company Name -->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr data-id="{{ $user->id }}">
                            <td data-field="id">{{ $user->id }}</td>
                                <td class="editable" data-field="name">{{ $user->name }}</td>
                                <td class="editable" data-field="phone">{{ $user->phone }}</td>
                                <td class="editable" data-field="type">{{ $user->type }}</td>
                                <td class="editable driver-column" data-field="iqama">{{ $user->iqama }}</td> <!-- Column for Driver Iqama -->
                                <td class="editable client-column" data-field="company_name">{{ $user->company_name }}</td> <!-- Column for Company Name -->
                                <td>
                                    <button class="btn btn-warning btn-sm edit-button">Edit</button>
                                    <form action="{{ route('deleteUser', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="form-group">
                        <label for="editName">Name</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editPhone">Phone</label>
                        <input type="text" class="form-control" id="editPhone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" >
                    </div>
                    <div class="form-group">
                        <label for="editUserType">User Type</label>
                        <select class="form-control" id="editUserType" name="type" required>
                            <option value="Admin">Admin</option>
                            <option value="Driver">Driver</option>
                            <option value="Client">Client</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editIqama">Driver Iqama</label>
                        <input type="text" class="form-control" id="editIqama" name="iqama">
                    </div>
                    <div class="form-group">
                        <label for="editCompanyName">Company Name</label>
                        <input type="text" class="form-control" id="editCompanyName" name="company_name" value="{{ $user->company_name }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save changes</button> <!-- Changed to type="submit" -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script>
$(document).ready(function () {
    // Initialize DataTable
    var dataTable = $('#dataTable').DataTable({
        "ordering": true,
            "order": [[0, "desc"]], // Sort by Date (4th column) in descending order by default
            "paging": true,
            "lengthMenu": [5, 10, 25, 30, 50, 100], // Control page length options
            "language": {
                "search": "Filter records:"
            }
    });


    // Show/Hide Columns Based on User Type
    function toggleColumns() {
        var userType = $('#userTypeFilter').val();
        
        // Hide the User Type column by default
        dataTable.column(3).visible(true); // Assuming User Type is the 4th column (index 3)
        if (userType === 'Driver') {
            dataTable.column('.client-column').visible(false); // Hide Company Name
            dataTable.column('.driver-column').visible(true);  // Show Driver Iqama
            dataTable.column(3).visible(false);                // Hide User Type
        } else if (userType === 'Client') {
            dataTable.column('.driver-column').visible(false);  // Hide Driver Iqama
            dataTable.column('.client-column').visible(true);   // Show Company Name
            dataTable.column(3).visible(false);                // Hide User Type
        } else if (userType === 'Admin') {
            dataTable.column('.driver-column').visible(false);  // Hide Driver Iqama
            dataTable.column('.client-column').visible(false);   // Hide Company Name
            dataTable.column(3).visible(true);                 // Show User Type
        } else if (userType === '') { // When 'All Users' is selected
            dataTable.column('.driver-column').visible(false);   // Hide Driver Iqama
            dataTable.column('.client-column').visible(false);    // Hide Company Name
            dataTable.column(3).visible(true);                 // Show User Type
        } else {
            dataTable.column('.driver-column').visible(true);   // Show Driver Iqama
            dataTable.column('.client-column').visible(true);    // Show Company Name
            dataTable.column(3).visible(true);                 // Show User Type
        }
    }

    // Initial toggle on page load
    toggleColumns();

    // Toggle columns on filter change
    $('#userTypeFilter').change(function() {
        toggleColumns();
    });

    // Handle the edit button click
    $('.edit-button').on('click', function () {
        var row = $(this).closest('tr');
        var id = row.data('id');

        // Populate the modal fields
        $('#user_id').val(id);
        $('#editName').val(row.find('td[data-field="name"]').text());
        $('#editPhone').val(row.find('td[data-field="phone"]').text());
        $('#editEmail').val(row.find('td[data-field="email"]').text());
        $('#editUserType').val(row.find('td[data-field="type"]').text());
        $('#editIqama').val(row.find('td[data-field="iqama"]').text());
        $('#editCompanyName').val(row.find('td[data-field="company_name"]').text());

        // Show the modal
        $('#editModal').modal('show');

        // Handle the form submission
        $('#editForm').off('submit').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission
            
            // Simple confirmation dialog
            var confirmSave = confirm("Are you sure you want to save changes?");
            if (confirmSave) {
                var formData = $(this).serialize();

                $.ajax({
                    url: '/users/' + id, // Update this URL based on your route
                    method: 'PUT',
                    data: formData,
                    success: function (response) {
                        // Update the table row with the new data
                        row.find('td[data-field="name"]').text($('#editName').val());
                        row.find('td[data-field="phone"]').text($('#editPhone').val());
                        row.find('td[data-field="email"]').text($('#editEmail').val());
                        row.find('td[data-field="type"]').text($('#editUserType').val());
                        row.find('td[data-field="iqama"]').text($('#editIqama').val());
                        row.find('td[data-field="company_name"]').text($('#editCompanyName').val());
                        $('#editModal').modal('hide');

                        // Optionally show success message
                        alert("User updated successfully!");
                    },
                    error: function (error) {
                        console.log('Error:', error);
                        alert("Failed to update user!");
                    }
                });
            } else {
                alert("Changes not saved.");
            }
        });
    });
});
</script>

</body>
</html>