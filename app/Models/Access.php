<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Compte;
use App\Models\Statut;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Access extends Model
{
    use HasFactory;
    protected $table = 'accesses';
    protected $primaryKey = 'ACC_ID_ACCESS';
    public $timestamps = false;

    protected $fillable = [];
    // Define the relationships with other models
    public function compte():BelongsTo
    {
        return $this->belongsTo(User::class, 'CTE_ID_COMPTE', 'CTE_ID_COMPTE');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'ROL_ID_ROLE', 'ROL_ID_ROLE');
    }

    public function statut()
    {
        return $this->belongsTo(Statut::class, 'STA_ID_STATUT', 'STA_ID_STATUT');
    }
    public function menus():BelongsToMany{
        return $this->belongsToMany(Menu::class);
    }
}
