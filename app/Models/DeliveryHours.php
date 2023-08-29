<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DeliveryHours extends Model
{
    use HasFactory;
    protected $table='delivery_hours';
    protected $primaryKey='HRS_ID_HOURS';
    public $timestamps= false;
    protected $fillable = ['HRS_TIME'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'delivery_hours_users', 'HRS_ID_HOURS', 'CTE_ID_COMPTE')
            ->whereHas('accesses', function ($query) {
                $query->where('ROL_ID_ROLE', 3); // 'Deliver' role ID is 4
            });
    }

}
