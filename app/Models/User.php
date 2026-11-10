<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Http\Filters\V1\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Permissions\V1\Abilities;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_manager',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_manager' => 'boolean',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'abilities' => Abilities::getAbilities($this),
        ];
    }

    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class, 'user_id');
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters): Builder
    {
        return $filters->apply($builder);
    }

    public function tokenCan(string $ability): bool
    {
        try {
            // Kunin ang buong payload mula sa JWT token
            $payload = auth('api')->payload();
            
            // Kunin ang 'abilities' array mula sa custom claims
            $abilities = $payload->get('abilities') ?? [];
            
            // Suriin kung ang hinahanap na ability ay nasa loob ng array
            return in_array($ability, $abilities, true);
        } catch (\Throwable $e) {
            return false;
        }
    }
}