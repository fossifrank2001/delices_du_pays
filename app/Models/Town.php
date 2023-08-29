<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Town extends Model
{
    use HasFactory;
    protected $table='towns';
    protected $primaryKey='TWN_ID_TOWN';
    public $timestamps= false;
    protected $fillable = ['TOWN_LIBELLE'];

    public function areas():HasMany{
        return $this->hasMany(Area::class, 'ARE_ID_AREA', 'ARE_ID_AREA');
    }
}
