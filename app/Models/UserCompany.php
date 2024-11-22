<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCompany extends Model
{
    use HasFactory;

    protected $table = 'users'; // Ensure this matches your table name

    // Method to fetch all drivers' names
    public static function getDriverNames()
    {
        return self::where('type', 'Driver')->get(['name']);
    }

    // Method to fetch clients with their company names
    public static function getClientsWithCompanyNames()
    {
        return self::where('type', 'Client')->get(['company_name']);
    }
}
