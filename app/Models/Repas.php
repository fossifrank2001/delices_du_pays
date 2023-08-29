<?php

namespace App\Models;

use App\Models\Image;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Categorie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Repas extends Model
{
    use HasFactory;
    protected $primaryKey = 'MEL_ID_MEAL';
    public $timestamps=false;
    protected $fillable = [
        'ART_ID_ARTICLE',
        'MEL_IN_PROMOTION',
        'MEL_REDUCTION',
        'MEL_CREATED_AT',
        'MEL_UPDATED_AT',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'ART_ID_ARTICLE', 'ART_ID_ARTICLE');
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'Commentable');
    }
    public function images():MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    // public function categories():BelongsToMany{
    //     return $this->belongsToMany(Categorie::class);
    // }
    public function categories()
    {
        return $this->belongsToMany(Categorie::class, 'categories_repas', 'MEL_ID_MEAL', 'CAT_ID_CATEGORY');
    }
}
