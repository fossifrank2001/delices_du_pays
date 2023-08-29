<?php

namespace App\Models;

use App\Models\Access;
use App\Models\Statut;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Hash; // Import the Hash facade
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Reliese\Coders\Model\Relations\HasOne;
use Tymon\JWTAuth\Claims\Collection;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, SoftDeletes, Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'CTE_ID_COMPTE';
    protected $fillable = [
            // 'STA_ID_STATUT',
            'CTE_FIRSTNAME',
            'CTE_LASTNAME',
            'CTE_EMAIL' ,
            'CTE_PHONE' ,
            'CTE_TOWN' ,
            'CTE_QUARTER',
            'CTE_PASSWORD',
            'CTE_TOKEN',
            'CTE_DATECREATE',
            'CTE_DATEUPDATE',
            'EMAIL_VERIFIED_AT'
    ];
    public $timestamps = false;
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'CTE_PASSWORD',
        'REMENBER_TOKEN',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'EMAIL_VERIFIED_AT' => 'datetime',
    ];
    public function deliveryHours(): BelongsToMany
    {
        return $this->belongsToMany(DeliveryHours::class, 'delivery_hours_users', 'CTE_ID_COMPTE', 'HRS_ID_HOURS')
            ->whereHas('accesses', function ($query) {
                $query->where('ROL_ID_ROLE', 3); // 'Deliver' role ID is 3
            });
    }

    public function accesses():HasMany
    {
        return $this->hasMany(Access::class, 'CTE_ID_COMPTE', 'CTE_ID_COMPTE');
    }

    public function statut():BelongsTo
    {
        return $this->belongsTo(Statut::class, 'STA_ID_STATUT', 'STA_ID_STATUT');
    }
    public function image():MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function comments():hasMany{
        return $this->hasMany(Comment::class, 'commentable');
    }
    public function notation():HasOne{
        return $this->morphOne(Notation::class, 'notable');
    }

    public function getFullname()
    {
        return $this->CTE_FIRSTNAME . ' ' . $this->CTE_LASTNAME . ' (' . $this->CTE_EMAIL . ')';
    }
    public static function findByUsername($username):array
    {
        if (strpos($username, '@') !== false) {
            $account = self::where('CTE_EMAIL', $username)->first();
            if($account){
                $account->load("accesses.role");
            }
            return [
                'account' =>  $account ,
                'column' =>'CTE_EMAIL',
            ];
        } else {
            $account = self::where('CTE_PHONE', $username)->first();
            if($account) {
                $account->load("accesses.role");
            }
            return [
                'account' => $account,
                'column' =>'CTE_PHONE',
            ];
        }
    }

    public function validatePassword($password)
    {
        return Hash::check($password, $this->CTE_PASSWORD);
    }

    // Your existing code...

    /**
     * Update the "remember_token" column with the provided token.
     *
     * @param string $token
     * @return void
     */
    public function updateRememberToken($token)
    {
        $this->REMENBER_TOKEN = $token;
        $this->save();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
