@include('common.sidebar')

<div class="container-fluid" id="container-wrapper">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card mb-4 shadow-lg border-light">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">User Profile</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <!-- Left Column: User Info -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h5 class="font-weight-bold">Name:</h5>
                                <p>{{ $user->name }}</p>
                            </div>
                            <div class="mb-3">
                                <h5 class="font-weight-bold">User Type:</h5>
                                <p>{{ $user->type }}</p>
                            </div>
                        </div>
                        <!-- Right Column: Order and Salary Info -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h5 class="font-weight-bold">Order Details (Current Month)</h5>
                                <p><strong>Orders Done:</strong> {{ $ordersDone }}</p>
                                <p><strong>Total Expenses:</strong> SAR {{ number_format($totalExpenses, 2) }}</p>
                                <p><strong>Basic Salary:</strong> SAR {{ number_format($basicSalary, 2) }}</p>
                                <p><strong>Remaining Salary:</strong> SAR {{ number_format($remainingSalary, 2) }}</p>
                            </div>
                        </div>
                    </div>
                  
                    <!-- Buttons for Print and Delete -->
                    <div class="text-center no-print">
                        <!-- Print Button -->
                        <button onclick="window.print()" class="btn btn-info btn-sm mx-2">
                            <i class="fa fa-print"></i> Print
                        </button>

                        <!-- Delete Button -->
                        <form action="{{ route('user.delete', ['id' => $user->id]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mx-2" onclick="return confirmDelete();">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>

                            <script>
                                function confirmDelete() {
                                    return confirm("Are you sure you want to delete this?");
                                }
                            </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('common.footerjqr')
<style>
    @media print {
        .no-print {
            display: none;
        }
    }
</style>