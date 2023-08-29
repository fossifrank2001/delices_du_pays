<?php

namespace App\Models;

use App\Models\Repas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Categorie extends Model
{
    use HasFactory;
    protected $table ='categories';
    protected $primaryKey = 'CAT_ID_CATEGORY';
    protected $fillable=[
        'CAT_LIBELLE',
    ];
    public $timestamps  = false;

    public function meals():BelongsToMany{
        return $this->belongsToMany(Repas::class, 'categories_repas', 'CAT_ID_CATEGORY', 'MEL_ID_MEAL');
    }
}
