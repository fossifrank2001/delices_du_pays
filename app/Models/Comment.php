<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comments';
    protected $primaryKey = 'COM_ID_COMMENT';
    protected $fillable=[
        "COM_CONTENT",
        "COMMENTABLE_type",
        "COMMENTABLE_id",
        'COM_CREATED_AT',
        'COM_UPDATED_AT',
        'CTE_ID_COMPTE'
    ];
    public $timestamps = false;

    public function commentable():MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that authored the comment.
     */
    public function compte()
    {
        return $this->belongsTo(User::class, "CTE_ID_COMPTE", 'CTE_ID_COMPTE');
    }
}
