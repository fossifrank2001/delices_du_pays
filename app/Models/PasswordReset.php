<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;
    public $table='password_resets';
    protected $primaryKey = 'PAS_RES_EMAIL';
    public $incrementing = false; // This tells Laravel that the primary key is not auto-incrementing
    public $timestamps = false; // Since you have 'PAS_RES_CREATED_AT' column, set timestamps to false

    protected $fillable=[
        'PAS_RES_EMAIL',
        'PAS_RES_TOKEN',
        'PAS_RES_CREATED_AT'
    ];
}
