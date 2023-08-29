<?php

namespace App\Models;

use App\Models\Image;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Boisson extends Model
{
    use HasFactory;
    protected $primaryKey = 'BEV_ID_BEVERAGE';
    public $timestamps = false;
    public function article()
    {
        return $this->belongsTo(Article::class, 'ART_ID_ARTICLE', 'ART_ID_ARTICLE');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'Commentable');
    }
    public function image():MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
