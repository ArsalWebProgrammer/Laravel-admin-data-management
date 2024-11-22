<?php
// app/Models/Expense.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseAdmin extends Model
{
    use HasFactory;

    protected $table = 'money_management';  // Specify your table name

    protected $fillable = [
        'expense_type',
        'driver_selection',
        'amount',
        'note',
        'created_by',
        'created_at',
    ];

    // Define a relationship if 'driver_selection' is a foreign key for a 'Driver' table
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_selection');
    }
}