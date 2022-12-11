<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    use HasFactory;
    protected $table = 'token';
    protected $primaryKey = 'id'; //Make id a primary key
    protected $fillable = [
        'email',
        'userToken'
    ];
}
