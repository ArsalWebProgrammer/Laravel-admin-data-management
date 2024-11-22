@include('common.sidebar')

<div class="container-fluid" id="container-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                </div>
                <div class="table-responsive p-3">
                    <div class="container">
                        
                        <h1>Salary Management</h1>
                        
                        <!-- Validation Errors Display -->
                        @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('saveMoneyManagement') }}">
                        @csrf
                        <div class="form-group">
                            <label>Select Type</label>
                            <select class="form-control" id="userType" name="user_type" required>
                                <option disabled selected value="">Select Type</option>
                                <option value="Driver">Driver</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>

                        <div class="form-group" id="adminField" style="display: none;">
                            <label>Select Admin</label>
                            <select class="form-control" name="id_user">
                                <option disabled selected value="">Select Admin</option>
                                @foreach($admin as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="driverField" style="display: none;">
                            <label>Select Driver</label>
                            <select class="form-control" name="id_user">
                                <option disabled selected value="">Select Driver</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Enter Salary</label>
                            <input type="number" class="form-control" name="salary" required placeholder="Enter Salary">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Save Record</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('common.footerjqr')

<script>
    document.getElementById('userType').addEventListener('change', function() {
        var adminField = document.getElementById('adminField');
        var driverField = document.getElementById('driverField');

        if (this.value === 'Admin') {
            adminField.style.display = 'block';
            driverField.style.display = 'none';
        } else if (this.value === 'Driver') {
            driverField.style.display = 'block';
            adminField.style.display = 'none';
        } else {
            adminField.style.display = 'none';
            driverField.style.display = 'none';
        }
    });
</script>