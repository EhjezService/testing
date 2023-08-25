<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Users  extends Authenticatable
{
    use HasFactory;
    protected $fillable=[
        'name','email_phone','password'
    ];
    protected $table='users';
    //primary key
    public $primaryKey='id';
    //timestamp
    public $timestamp=true;

    // Define the reverse one-to-one relationship with Hall model
    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }
}
