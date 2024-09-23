<?php

namespace App\Models;

use App\Models\Scopes\IsActiveScope;
use App\Models\Scopes\IsNotYetDeletedScope;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $table        = 'users';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = true;

    protected $fillable = [
        'username',
        'password',
        'name'
    ];

    protected static function booted(): void
    {
        parent::booted();
        self::addGlobalScopes([
            new IsActiveScope,
            new IsNotYetDeletedScope
        ]);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'user_id', 'id');
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function getAuthIdentifier()
    {
        return $this->username;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->token;
    }

    public function setRememberToken($value): void
    {
        $this->token = $value;
    }


    public function getRememberTokenName()
    {
        return 'token';
    }
}
