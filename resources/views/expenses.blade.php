@include('common.sidebar')

<div class="container-fluid" id="container-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daily Expense Tracker</h6>
                </div>
                <div class="table-responsive p-3">
                    <div class="container">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('expenses.store') }}">
                            @csrf
                            
                            <!-- Expense Type Selection -->
                            <div class="form-group">
                                <label for="expenseType">Expense Type</label>
                                <select id="expenseType" name="expense_type" class="form-control" required>
                                    <option disabled selected value="">Select Expense Type</option>
                                    <option value="general" {{ old('expense_type') == 'general' ? 'selected' : '' }}>General Expense</option>
                                    <option value="driver" {{ old('expense_type') == 'driver' ? 'selected' : '' }}>Driver Expense</option>
                                </select>
                                @error('expense_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Driver Selection (Visible only if "Driver Expense" is selected) -->
                            <div class="form-group" id="driverSelect" style="display: none;">
                                <label for="driver">Select Driver</label>
                                <select id="driver" name="driver_id" class="form-control">
                                    <option disabled selected value="">Select Driver</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                                    @endforeach
                                </select>
                                @error('driver_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Amount Field -->
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" id="amount" name="amount" class="form-control" required placeholder="Enter Amount" value="{{ old('amount') }}">
                                @error('amount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Note Field -->
                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea id="note" name="note" class="form-control" rows="3" required placeholder="Add a note about this expense">{{ old('note') }}</textarea>
                                @error('note')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date Field -->
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" id="date" name="date" class="form-control" required value="{{ old('date') }}">
                                @error('date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Add Expense</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('common.footerjqr')

<!-- JavaScript to toggle driver selection based on expense type -->
<script>
    document.getElementById('expenseType').addEventListener('change', function() {
        const driverSelect = document.getElementById('driverSelect');
        if (this.value === 'driver') {
            driverSelect.style.display = 'block';
        } else {
            driverSelect.style.display = 'none';
        }
    });
</script>
