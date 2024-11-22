<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyManagement extends Model
{
    use HasFactory;

    protected $table = 'money_management';

    protected $fillable = ['user_type', 'id_user', 'salary',];
}