<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'middle_name', 'last_name', 'email', 'phone','birthday', 'ssn', 'gender', 'position', 'salary', 'address', 'address2', 'city', 'state', 'zip', 'img', 'start_date', 'end_date'];
}
