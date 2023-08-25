<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HallImage extends Model
{
    use HasFactory;
    protected $fillable=[
      'hall_id',
      'url'
    ];

}
