<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\UserCompany;
use App\Events\NewOrderCreated;
use Illuminate\Support\Facades\Log;
use App\Notifications\AdminActionNotification;
use App\Models\User; // For sending notifications to users

class OrderController extends Controller
{
    public function create(Request $request)
{
    $order = null;

    if ($request->has('id')) {
        $order = Order::find($request->input('id'));
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }
    }

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

    return view('add-order', compact('order', 'companies', 'drivers'));
}

public function addOrder(Request $request)
{
    // Log the request data for debugging
    \Log::info($request->all());

    $request->validate([
        'company_name' => 'required', // This will hold the company ID
        'from_location' => 'required',
        'destination' => 'required',
        'total_rate' => 'required|numeric',
        'payment_status' => 'required',
        'amount_paid' => 'nullable|numeric',
        'amount_remaining' => 'nullable|numeric',
        'driver' => 'required', // This will hold the driver ID
        'driver_trip' => 'nullable|numeric',

    ]);

    try {
        // Create the order
        $order = new Order();
        $order->company_name = $request->input('company_name'); // Store company ID in company_name column
        $order->location_from = $request->input('from_location');
        $order->location_where = $request->input('destination');
        $order->total_rate = $request->input('total_rate');
        $order->payment = $request->input('payment_status');
        $order->amount_paid = $request->input('amount_paid');
        $order->amount_remaining = $request->input('amount_remaining');
        $order->driver = $request->input('driver'); // Store driver ID in driver column
        $order->driver_trip = $request->input('driver_trip'); // Store driver ID in driver column
        $order->user_id = Auth::id(); // Store the authenticated user's ID

        // Save the order
        $order->save();
        broadcast(new NewOrderCreated($order))->toOthers();

        return redirect()->back()->with('success', 'Order added successfully!');
    } catch (\Exception $e) {
        // Log the error and return a JSON error response
        \Log::error($e->getMessage());
        return response()->json(['error' => 'Failed to add order.'], 500);
    }
}

    // Fetch and display all orders
    public function orderprint()
    {
        // Fetch all orders along with the user who created each order
        $orders = Order::with('user')->get();
        $driver = Order::with('driver1')->get();
        $client = Order::with('client')->get();

        // Return the view with the orders
        return view('all-orders', compact('orders'));
        // return $driver;
    }

    public function userorder($id) {
        // Retrieve the specific order from allorders
        $order = Order::findOrFail($id); // Assuming Order model is connected to allorders
        $driver = Order::with(['driver1'])->findOrFail($id);
        $client = Order::with(['client'])->findOrFail($id);
        
        // Fetch company names and driver names from the users table
        // $ucompanies = UserCompany::getClientsWithCompanyNames(); // Method to get clients with their company names
        // $udrivers = UserCompany::getDriverNames(); // Method to get all drivers' names
        
    
        // Pass the data to the view for display and editing
        return view('show', compact('order', 'driver'));
        // return $driver; 
    }


        public function editorder($id)
        {
            return view('addorder', compact('id')); // Pass the id to the view
        }
    

    
    public function editorders($id)
    {
        $uorderid = Order::find($id);
        $companies = DB::table('users')
            ->where('type', 'Client')
            ->whereNotNull('company_name')
            ->select('id', 'company_name as name')
            ->get();
    
        $drivers = DB::table('users')
            ->where('type', 'Driver')
            ->select('id', 'name')
            ->get();
    
        if (!$uorderid) {
            return redirect()->back()->with('error', 'Order not found.');
        }
    
        return view('edit-order', compact('uorderid', 'companies', 'drivers'));
    }

    public function updateOrders(Request $request, $id)
{
    $request->validate([
        'company_name' => 'required|integer', // Ensure it matches the ID
        'from_location' => 'required|string|max:255',
        'destination' => 'required|string|max:255',
        'total_rate' => 'required|numeric',
        'payment_status' => 'required|string',
        'amount_paid' => 'nullable|numeric',
        'amount_remaining' => 'nullable|numeric',
        'driver_name' => 'required|integer', // Ensure it matches the ID
    ]);

    $order = Order::findOrFail($id);

    $order->company_name = $request->input('company_name');
    $order->location_from = $request->input('from_location');
    $order->location_where = $request->input('destination');
    $order->total_rate = $request->input('total_rate');
    $order->payment = $request->input('payment_status');
    $order->amount_paid = $request->input('amount_paid') ?? 0;
    $order->amount_remaining = $request->input('amount_remaining') ?? 0;
    $order->driver = $request->input('driver_name');

    try {
        $order->save();
        return redirect()->route('orders.show', ['id' => $id])->with('success', 'Order updated successfully!');
    } catch (\Exception $e) {
        Log::error($e->getMessage());
        return redirect()->back()->with('error', 'Failed to update the order.');
    }
}

    // Existing show method for the order
    public function show1($id)
    {
        // Fetch the order data based on the ID
        $order = Order::find($id);

        // Check if order exists
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        // Pass the data to the show view
        return view('show', compact('order'));
    }

    public function destroy($id)
{
    // Find the order by ID
    $order = Order::find($id);

    if ($order) {
        // Delete the order
        $order->delete();

        // Redirect with a success message
        return redirect()->route('all-orders')->with('success', 'Order deleted successfully');
    }

    // If the order is not found, return an error message
    return redirect()->route('all-orders')->with('error', 'Order not found');
}

}   