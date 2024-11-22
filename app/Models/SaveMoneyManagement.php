<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveMoneyManagement extends Model
{
    use HasFactory;

    protected $table = 'money_management';

    protected $fillable = [
        'expense_type', 
        'driver_selection', 
        'expense_amount', 
        'created_by',
        'note', 
        'created_at',
        'updated_at'
    ];

    // Optional: Define a relationship to the User model if driver_selection references a user
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_selection');
    }
}
