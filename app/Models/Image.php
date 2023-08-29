<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
/**
     *  @OA\Schema(
     *    schema="Image",
     *    title="Image Model",
     *    description="Model handling  image of a resource",
     *    @OA\Property(property="IMG_ID_IMAGE", type="integer", format="int64"),
     *    @OA\Property(property="IMG_PATH", type="string"),
     *    @OA\Property(property="IMAGEABLE_type", type="string"),
     *    @OA\Property(property="IMAGEABLE_id", type="interger", format="int64"),
     *  )
     */
class Image extends Model
{
    use HasFactory;
    protected $table = 'images';
    protected $primaryKey = 'IMG_ID_IMAGE';
    protected $fillable=[
        "IMG_PATH",
        "IMAGEABLE_type",
        "IMAGEABLE_id"
    ];
    public $timestamps = false;

    public function imageable():MorphTo
    {
        return $this->morphTo();
    }
}
