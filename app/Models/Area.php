<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory;
    protected $table='areas';
    protected $primaryKey='ARE_ID_AREA';
    public $timestamps= false;
    protected $fillable = ['ARE_LIBELLE'];

    public function town():BelongsTo{
        return $this->belongsTo(Town::class, 'ARE_ID_AREA', 'ARE_ID_AREA');
    }
}
