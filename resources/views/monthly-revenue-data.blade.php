@include('common.sidebar')
<!-- Include Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid" id="container-wrapper">
    <div class="row">
        <div class="col-lg-12">
        <div class="row mb-3">
            <!-- Total Earnings (Monthly) Card Example -->

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card h-100">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Total Earnings (Monthly)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalEarnings, 2) }} SAR</div>
                    <div class="mt-2 mb-0 text-muted text-xs">
                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ $changePercentage }}%</span>
                        <span>{{ $changeText }}</span>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-calendar fa-2x text-primary"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Total Expense (Salaries + Other Expenses + Trip Expenses) -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card h-100">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Total Expense (Monthly)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalExpense, 2) }} SAR</div>
                    <div class="mt-2 mb-0 text-muted text-xs">
                        <span class="{{ $changePercentage >= 0 ? 'text-success' : 'text-danger' }} mr-2">
                            <i class="{{ $changePercentage >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down' }}"></i>
                            {{ $changePercentage }}%
                        </span>
                        <span>{{ $changeText }}</span>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-shopping-cart fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Remaining Payments -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card h-100">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Remaining Payments</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($remainingPayments, 2) }} SAR</div>
                    <div class="mt-2 mb-0 text-muted text-xs">
                        <span class="text-warning mr-2">
                            <i class="fas fa-exclamation-triangle"></i> Pending payments
                        </span>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-wallet fa-2x text-info"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Total Profit (Earnings - Expenses) -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card h-100">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Total Profit</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalProfit, 2) }} SAR</div>
                    <div class="mt-2 mb-0 text-muted text-xs">
                        <span class="{{ $changePercentage >= 0 ? 'text-success' : 'text-danger' }} mr-2">
                            <i class="{{ $changePercentage >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down' }}"></i>
                            {{ $changePercentage }}%
                        </span>
                        <span>{{ $changeText }}</span>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-dollar-sign fa-2x text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</div>

            
<div class="col-xl-12 col-lg-12">
    <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Monthly Recap Report</h6>
        </div>
        <div class="card-body">
            <canvas id="monthlyRevenueChart"></canvas>
        </div>
    </div>
</div>
</div>
</div>
</div>

@include('common.footerjqr')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const year = 2024; // Set your desired year here

    // Fetch monthly revenue data
    fetch(`{{ route('monthlyRevenue.data', ['year' => 2024]) }}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Debugging: Check what data was received
            console.log(data); // Log the data to the console

            if (data.length === 0) {
                console.error('No data returned from backend');
                return;
            }

            const months = data.map(item => item.month);  // Extract month names
            const revenueData = data.map(item => item.revenue);  // Extract revenue values

            // Render the Chart
            const ctx = document.getElementById("monthlyRevenueChart").getContext("2d");
            const monthlyRevenueChart = new Chart(ctx, {
                type: 'line', // For area chart
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Monthly Revenue',
                        data: revenueData,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Light green area
                        borderColor: 'rgba(75, 192, 192, 1)', // Darker green line
                        borderWidth: 1,
                        fill: true // This makes the area chart
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Revenue'
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching data:', error));
});

</script>