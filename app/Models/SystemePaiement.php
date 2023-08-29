<?php

namespace App\Models;

use App\Models\ModePaiement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemePaiement extends Model
{
    use HasFactory;
    protected $table = 'systeme_paiements';
    protected $primaryKey = 'SDP_ID_SYSTEM_PAIEMENT';
    public $timestamps = false;

    protected $fillable = [
        'MDP_ID_MOD_PAIEMENT',
        'SDP_LIBELLE',
    ];

    public function modePaiement()
    {
        return $this->belongsTo(ModePaiement::class, 'MDP_ID_MOD_PAIEMENT', 'MDP_ID_MOD_PAIEMENT');
    }
}
