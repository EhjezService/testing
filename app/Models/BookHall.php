<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookHall extends Model
{
    use HasFactory;
    protected $fillable = [
        'hall_id',
        'user_id',
        'date',
        'beneficial',
        'type',
        'payer',    
        'account_type',
        'pay_photo',
    ];
}
