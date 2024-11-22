<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import the Auth facade
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;

class RegisterController extends Controller
{
    // Display users based on user type filter
    public function index(Request $request)
    {
        // Get the user type filter from the request
        $userType = $request->get('user_type');

        // Fetch users based on the filter
        $users = User::when($userType, function ($query) use ($userType) {
            return $query->where('type', $userType);
        })->get();

        return view('home', compact('users'));
    }

    public function registeruser(Request $request)
    {
        // Validate the form data with dynamic validation rules based on usertype
        $rules = [
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'usertype' => 'required|in:Admin,Driver,Client', // User types can be Admin, Driver, or Client
            'pass' => 'required|min:8|confirmed', // Ensure passwords match and length is at least 8
        ];
    
        // Add conditional rules based on the usertype
        if ($request->usertype === 'Driver') {
            $rules['iqama'] = 'required|digits:10'; // Iqama is required for Driver
        }
    
        if ($request->usertype === 'Client') {
            $rules['company_name'] = 'required|string|max:255'; // Company name is required for Client
        }
    
        // Add the email validation only if it's provided or required for the usertype
        if ($request->usertype !== 'Driver') { // Only validate email for non-Driver users
            $rules['email'] = 'required|email|unique:users,email';
        }
    
        // Validate the form data with dynamic rules
        $validatedData = $request->validate($rules);
    
        // Prepare the data to be inserted into the database
        $data = [
            'name' => $validatedData['fullname'],
            'email' => $validatedData['email'] ?? null, // Email can be null if not required (e.g., for Driver)
            'phone' => $validatedData['phone'],
            'type' => $validatedData['usertype'],
            'password' => bcrypt($validatedData['pass']), // Hash the password
            'iqama' => $validatedData['usertype'] === 'Driver' ? $validatedData['iqama'] : null, // Store Iqama only for Driver
            'company_name' => $validatedData['usertype'] === 'Client' ? $validatedData['company_name'] : null, // Store company name only for Client
            'remember_token' => csrf_token(),
        ];
    
        // Insert the data into the database
        $result = DB::table('users')->insert($data);
    
        // Return a success or error response based on the result
        if ($result) {
            return response()->json(['success' => 'User created successfully!']);
        } else {
            return response()->json(['error' => 'Data not inserted'], 500);
        }
    }
    

    // Login user
    public function loginuser(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8', // Adjusted to match signup requirements
        ]);
       
        // Check if the user exists and the password is correct
        $user = DB::table('users')->where('email', $validatedData['email'])->first();
      
        if ($user && Hash::check($request->password, $user->password)) {
            // Log the user in
            Auth::loginUsingId($user->id);

            \Log::info('User logged in successfully with email: ' . $validatedData['email']);

            return redirect()->route('monthlyRevenue.page')->with('success', 'Logged in successfully.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',]);
        // return redirect()->json(['error' => 'The provided credentials do not match our records.'], 500);
        
            // Log::error($e->getMessage());
            // return response()->back()->with('error', '!Failed to login: The provided credentials do not match our records.' . $e->getMessage());
        
    }

    // Get all users
    public function usertable()
    {
        $result = DB::table('users')->get();
        return view('home', ['users' => $result]);
    }

    // Edit or Update User
    public function updateUser(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|unique:users,email,' . $id,
            'type' => 'required|in:Client,Admin,Driver',
            'iqama' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
        ]);

        $result = DB::table('users')->where('id', $id)->update($validatedData);

        if ($result) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false], 400);
        }
    }

    // Delete User
    public function deleteUser($id)
    {
        $result = DB::table('users')->where('id', $id)->delete();

        if ($result) {
            return redirect()->back()->with('success', 'User deleted successfully.');
        } else {
            return redirect()->back()->withErrors(['message' => 'Failed to delete user.']);
        }
    }

    public function showClients()
    {
        // Fetch clients from the 'users' table where 'type' is 'client'
        $clients = User::where('type', 'client')->get();
        
        // Pass clients to the view
        return view('all-clients', compact('clients')); 
    }
    
    public function showClientProfile($id)
{
    // Fetch client information from the 'users' table
    $client = User::find($id);  // Find the client by ID
    
    // Ensure client exists
    if (!$client) {
        return redirect()->back()->with('error', 'Client not found');
    }

    // Fetch the orders for the client from the 'allorders' table
    $orders = Order::where('company_name', $client->id)->get();  // Fetch orders where user_id matches the client ID

    // Get company info from the 'users' table
    $companyName = $client->company_name ?? 'N/A';
    $companyPhone = $client->phone ?? 'N/A';
    $companyId = $client->id ?? 'N/A';

    // Calculate remaining balance for the current month and last month
    $currentMonth = now()->month;
    $lastMonth = now()->subMonth()->month;

    // Filter orders for current month and last month
    $currentMonthOrders = $orders->filter(function($order) use ($currentMonth) {
        return $order->created_at->month == $currentMonth;
    });

    $lastMonthOrders = $orders->filter(function($order) use ($lastMonth) {
        return $order->created_at->month == $lastMonth;
    });

    // Sum remaining balance for both current and last month
    $remainingThisMonth = $currentMonthOrders->sum('amount_remaining');
    $remainingLastMonth = $lastMonthOrders->sum('amount_remaining');

    // Calculate remaining balance for each month
    $remainingAmounts = $orders->groupBy(function($order) {
        return $order->created_at->format('Y-m'); // Group by Year-Month
    })->map(function($monthOrders) {
        return $monthOrders->sum('amount_remaining'); // Sum the remaining amounts for each month
    });

    // Count the total number of orders for the client
    $totalOrders = $orders->count();

    // Return the data to the view
    return view('client-profile', compact('client', 'orders', 'remainingThisMonth', 'remainingLastMonth', 'remainingAmounts', 'totalOrders', 'companyName', 'companyPhone', 'companyId'));
}


}
