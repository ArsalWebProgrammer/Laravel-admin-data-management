<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'allorders'; // Specify the correct table name

    protected $fillable = [
        'company_name',
        'location_from',
        'location_where',
        'total_rate',
        'payment',
        'amount_remaining',
        'amount_paid',
        'driver', // Make sure this matches your table structure
        'user_id',   // Add user_id if it's in the orders table
    ];

    // Relationship to get the client (user who created the order)
    public function client()
    {
        return $this->belongsTo(User::class, 'company_name'); // Assuming 'client_id' references 'id' in users

    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin'); // Assuming 'client_id' references 'id' in users
    }

  
    
    public function driver1()
{
    return $this->belongsTo(User::class, 'driver'); // Assuming 'driver_id' is the foreign key for the driver
}

public function user()
{
    return $this->belongsTo(User::class, 'user_id'); // Assuming 'user_id' is the foreign key for the creator
}


}
