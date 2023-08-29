<?php

namespace App\Models;

use App\Models\Access;
use App\Models\Compte;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Statut extends Model
{
    use HasFactory;
    protected $table = 'statuts';
    protected $primaryKey = 'STA_ID_STATUT';
    public $timestamps = false;

    protected $fillable = [
        'STA_LIBELLE',
    ];

    public function accesses()
    {
        return $this->hasMany(Access::class, 'STA_ID_STATUT', 'STA_ID_STATUT');
    }

    public function comptes()
    {
        return $this->hasMany(User::class, 'STA_ID_STATUT', 'STA_ID_STATUT');
    }
}
