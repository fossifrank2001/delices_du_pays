<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notation extends Model
{
    use HasFactory;
    public function notable():MorphTo
    {
        return $this->morphTo();
    }
}
