<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\User;
use App\Models\ExpenseAdmin;
use App\Models\UserCompany;
use App\Events\NewOrderCreated;
use Illuminate\Support\Facades\Log;
use App\Models\SaveMoneyManagement;

class MoneyController extends Controller
{
    public function salary(Request $request)
    {
        $order = null;
    
        // Fetch companies where user type is 'Client'
        $companies = DB::table('users')
            ->where('type', 'Client')
            ->select('id', 'company_name as name')
            ->get();
    
        // Fetch drivers where user type is 'Driver'
        $drivers = DB::table('users')
            ->where('type', 'Driver')
            ->select('id', 'name')
            ->get();
    
        $admin = DB::table('users')
            ->where('type', 'admin')
            ->select('id', 'name')
            ->get();
    
        return view('salary', compact('companies', 'drivers', 'admin'));
        // return $admin;
    }
    
    public function saveMoney(Request $request)
    {
        // Validate the input
        $request->validate([
            'user_type' => 'required',
            'id_user' => 'required|exists:users,id',
            'salary' => 'required|numeric',
        ]);
    
        // Check if a record already exists for the given user
        $existingRecord = MoneyManagement::where('id_user', $request->id_user)
                                          ->where('user_type', $request->user_type)
                                          ->first();
    
        if ($existingRecord) {
            // Trigger a custom validation error if the record already exists
            return back()->withErrors(['user_exists' => 'A record already exists for this user.']);
        }
    
        // Create a new MoneyManagement record if it doesn't already exist
        MoneyManagement::create([
            'user_type' => $request->user_type,
            'id_user' => $request->id_user,
            'salary' => $request->salary,
        ]);
    
        return redirect()->back()->with('success', 'Record saved successfully!');
        }

        public function showMoneyManagement()
        {
            // Fetch records with users joined
            $records = DB::table('money_management')
                ->join('users', 'money_management.id_user', '=', 'users.id')
                ->select('money_management.*', 'users.id as user_id', 'users.name as user_name', 'money_management.user_type', 'money_management.salary')
                ->orderBy('money_management.created_at', 'desc')
                ->get()
                ->map(function ($record) {
                    $record->created_at = Carbon::parse($record->created_at)->format('Y-m-d');
                    return $record;
                });
        
            return view('money-management', compact('records'));
    }

//     public function expen()
// {
//     $drivers = User::where('type', 'driver')->get();  // Adjust this query based on how you store driver roles
//     $expenses = Expense::latest()->get();

//     return view('expenses', compact('drivers', 'expenses'));
// }

// Display the form and list all expenses
// Display the daily expenses page
    public function showDailyExpenses()
    {
        $expenses = SaveMoneyManagement::whereNotNull('note')
            ->orderBy('created_at', 'desc')
            ->get();

            $admin = DB::table('users')
            ->where('type', 'admin')
            ->select('id', 'name')
            ->get();

        return view('daily-expense', compact('expenses', 'admin'));
    }

    // Display the expense form and list of expenses
    public function index()
    {
        $drivers = User::where('type', 'driver')->get();
        $expenses = SaveMoneyManagement::whereNotNull('note')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('expenses', compact('drivers', 'expenses'));

    }

    public function store(Request $request)
{
    // Get the ID of the currently authenticated user
    $userId = auth()->id();

    // Store the expense in the database
    SaveMoneyManagement::create([
        'expense_type' => $request->expense_type,
        'expense_amount' => $request->amount,
        'driver_selection' => $request->expense_type === 'driver' ? $request->driver_id : null,
        'note' => $request->note,
        'created_at' => $request->date,
        'created_by' => $userId, // Store the user ID who added the expense
    ]);

   

    return redirect()->route('expenses.store')->with('success', 'Expense added successfully!');
}


     /**
     * Remove the specified expense from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Find the expense by its ID
        $expense = ExpenseAdmin::find($id);

        // Check if the expense exists
        if (!$expense) {
            return redirect()->route('expenses.index')->with('error', 'Expense not found.');
        }

        // Delete the expense
        $expense->delete();

        // Redirect back with a success message
        return redirect()->route('d-expenses')->with('success', 'Expense deleted successfully.');
    }

    //monthly revenue
    public function calculateMonthlyRevenue($year)
    {
        $monthlyRevenueData = [];
        $currentMonth = Carbon::now()->month;  // Get current month dynamically
    
        // Loop through each month to calculate revenue
        for ($month = 1; $month <= $currentMonth; $month++) {  // Limit the loop to current month
            // Step 1: Calculate total revenue from orders for the month
            $monthlyOrderRevenue = DB::table('allorders')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum(DB::raw('total_rate - driver_trip - amount_remaining'));
    
            // Step 2: Calculate total salaries and expenses for the month
            $monthlySalariesAndExpenses = DB::table('money_management')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum(DB::raw('salary + expense_amount'));
    
            // Step 3: Calculate net revenue for the month
            $netRevenue = $monthlyOrderRevenue - $monthlySalariesAndExpenses;
    
            // If there's no revenue, set it to 0
            if ($netRevenue === null) {
                $netRevenue = 0;
            }
    
            // Add the calculated revenue data to the array for each month
            $monthlyRevenueData[] = [
                'month' => Carbon::create($year, $month, 1)->format('F'),
                'revenue' => $netRevenue,
            ];
        }
    
        // Return as JSON
        return response()->json($monthlyRevenueData);
    }

    public function getMonthlyRevenuePage()
{
    // 1. Fetch salary data for the current month
    $salariesData = SaveMoneyManagement::whereMonth('created_at', date('m'))
        ->whereNotNull('salary')  // Ensure salary is not null
        ->get();

    // Sum the salaries from the data
    $totalSalaries = $salariesData->sum('salary');

    // 2. Fetch expense data for the current month (excluding salary)
    $otherExpenses = SaveMoneyManagement::where('expense_type', '!=', 'salary')
        ->whereMonth('created_at', date('m'))
        ->sum('expense_amount');

    // 3. Fetch total trip expenses for the current month
    $totalTripExpense = DB::table('allorders')
        ->whereMonth('created_at', date('m'))
        ->sum('driver_trip');

    // 4. Total Earnings (excluding 'Remaining' payments) from all orders for the current month
    $totalEarnings = DB::table('allorders')
        ->whereMonth('created_at', date('m'))
        ->where('payment', '!=', 'Remaining')  // Exclude 'Remaining' payments
        ->sum('total_rate');

    // 5. Calculate total expenses (salaries + other expenses + trip expenses)
    $totalExpense = $totalSalaries + $otherExpenses + $totalTripExpense;

    // 6. Calculate total profit (Earnings - Total Expenses)
    $totalProfit = $totalEarnings - $totalExpense;

    // 7. Calculate Remaining Payments (only Pending orders)
    $remainingPayments = DB::table('allorders')
        ->where('payment', 'Pending')
        ->whereMonth('created_at', date('m'))
        ->sum('amount_remaining');

    // 8. Calculate the previous month's earnings for dynamic comparison
    $previousMonthEarnings = DB::table('allorders')
        ->whereMonth('created_at', date('m', strtotime('-1 month')))
        ->sum('total_rate');

    // 9. Calculate the percentage change and determine if it's profit or loss
    $monthlyChange = $totalEarnings - $previousMonthEarnings;
    $isProfit = $monthlyChange >= 0;
    $changePercentage = ($previousMonthEarnings != 0) ? round(($monthlyChange / $previousMonthEarnings) * 100, 2) : 0;
    $changeText = $isProfit ? "Profit this month" : "Loss this month";
    $changeIcon = $isProfit ? "fa-arrow-up text-success" : "fa-arrow-down text-danger";

    // Return the view with the necessary data
    return view('monthly-Revenue-data', compact(
        'totalEarnings', 
        'totalExpense', 
        'totalProfit', 
        'remainingPayments', 
        'changePercentage', 
        'changeText', 
        'changeIcon',
        'totalSalaries', 
        'totalTripExpense', 
        'otherExpenses'
    ));
}

// UserController.php
public function showProfile($id)
{
    // Fetch user details from the 'users' table
    $user = DB::table('users')
        ->where('id', $id) // Assuming 'id' is the primary key in 'users' table
        ->select('name', 'type') // Fetch the user's name and user type
        ->first();

        $user = User::find($id);
    // If user is not found, handle it
    if (!$user) {
        return redirect()->back()->with('error', 'User not found');
    }

    // Fetch the orders related to this user (Driver) for the current month
    $orders = DB::table('allorders')
        ->where('driver', $id) // Assuming 'driver' is the foreign key in 'allorders' table
        ->whereMonth('created_at', date('m')) // Filter by current month
        ->get();

    // Count the number of orders assigned to the driver this month
    $ordersDone = $orders->count();

    // Calculate the total amount received (where payment is not 'Remaining')
    $totalAmountReceived = $orders->where('payment', '!=', 'Remaining')->sum('total_rate');

    // Calculate total expenses (driver trip expenses) using the 'driver' column
    $totalExpenses = DB::table('money_management')
        ->where('driver_selection', $id) // This fetches expenses related to the driver
        ->sum('expense_amount');

      // Fetch the basic salary for the driver from 'money_management' table using id_user
      $basicSalary = DB::table('money_management')
      ->where('id_user', $id) // Fetch salary data using the id_user field
      ->sum('salary'); // Assuming 'salary' column stores the basic salary amount


    
  // Calculate the remaining salary by subtracting the expenses from the basic salary
  $remainingSalary = $basicSalary - $totalExpenses;

  // Return the view with necessary data
  return view('user-profile', compact('user', 'ordersDone', 'totalAmountReceived', 'totalExpenses', 'basicSalary', 'remainingSalary'));
}

public function deletesalary($id)
{
    try {
        // Delete all records from money_management where id_user matches the given ID
        $deletedRows = DB::table('money_management')->where('id_user', $id)->delete();

        if ($deletedRows) {
            
            // Redirect back with a success message
    return redirect()->route('management')->with('success', 'User profile deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'No records found to delete.');
        }
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
    
}
}
