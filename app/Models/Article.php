<?php

namespace App\Models;

use App\Models\Repas;
use App\Models\Boisson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Article extends Model
{
    use HasFactory;
    protected $primaryKey = 'ART_ID_ARTICLE';
    public $timestamps = false;
    public function meal()
    {
        return $this->hasOne(Repas::class,'ART_ID_ARTICLE','ART_ID_ARTICLE');
    }

    public function beveurage()
    {
        return $this->hasOne(Boisson::class,'ART_ID_ARTICLE','ART_ID_ARTICLE');
    }
}
