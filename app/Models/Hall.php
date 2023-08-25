<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    use HasFactory;
    protected $fillable = [
        'profile',
        'name',
        'user_id',
        'conditions',
        // 'phone',
        'events',
        'services',
        'description',
        'social_media',
        'amplitude',
        'location',
        'price',

    ];

    // Define the one-to-one relationship with User model
    public function user()
    {
        return $this->hasOne(Users::class);
    }
}