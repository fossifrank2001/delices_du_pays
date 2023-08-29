<?php

namespace App\Models;

use App\Models\SystemePaiement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModePaiement extends Model
{
    use HasFactory;
    protected $table = 'mode_paiements';
    protected $primaryKey = 'MDP_ID_MOD_PAIEMENT';
    public $timestamps = false;

    protected $fillable = [
        'MDP_LIBELLE',
    ];

    public function systemePaiements()
    {
        return $this->hasMany(SystemePaiement::class, 'MDP_ID_MOD_PAIEMENT', 'MDP_ID_MOD_PAIEMENT');
    }
}

