<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'menus';
    protected $primaryKey = 'MEN_ID_MENU';
    public $timestamps = false;

    protected $fillable = [
        'MEN_ID_MENU',
        'MEN_LIBELLE',
        'MEN_ICON',
    ];

    public function accesses():BelongsToMany{
        return $this->belongsToMany(Access::class, 'access_menu', 'MEN_ID_MENU', 'ACC_ID_ACCESS');
    }
}
