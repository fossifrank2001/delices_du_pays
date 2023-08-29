<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Reliese\Coders\Model\Relations\BelongsTo;
use Reliese\Coders\Model\Relations\BelongsToMany;

/**
 *  @OA\Schema(
 *    schema="Role",
 *    title="Role Model",
 *    description="Model for the Role of an User",
 *    @OA\Property(property="ROL_ID_ROLE", type="integer", format="int64"),
 *    @OA\Property(property="ROL_LIBELLE", type="string"),
 *  )
 */
class Role extends Model
{
    use HasFactory;
    public $table = 'roles';
    protected $primaryKey = 'ROL_ID_ROLE';
    public $timestamps = false;

    protected $fillable = [
        'ROL_LIBELLE'
    ];

    protected $casts = [
        'ROL_LIBELLE' => 'string'
    ];

    public static array $rules = [
        'ROL_LIBELLE' => 'required|string|max:255',
    ];

    public function  permissions(){
        return $this->belongsToMany(Permission::class, 'permission_role', 'ROL_ID_ROLE', 'PER_ID_PERMISSION');
    }
    public function  menus(){
        return $this->belongsToMany(Menu::class, 'menus_roles', 'ROL_ID_ROLE', 'MEN_ID_MENU');
    }
    public function accesses():HasMany{
        return $this->hasMany(Access::class,'ROL_ID_ROLE', 'ROL_ID_ROLE');
    }
}
