<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Reliese\Coders\Model\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    public $table= 'permissions';
    public $timestamps= false;
    protected $primaryKey = 'PER_ID_PERMISSION';
    protected $fillable = ['PER_LIBELLE'];

    /**
     * The roles that belong to the permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'PER_ID_PERMISSION', 'ROL_ID_ROLE');
    }
}
