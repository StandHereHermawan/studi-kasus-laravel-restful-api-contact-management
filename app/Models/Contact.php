<?php

namespace App\Models;

use App\Models\Scopes\IsActiveScope;
use App\Models\Scopes\IsNotYetDeletedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'contacts';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = true;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
    ];

    protected static function booted(): void
    {
        parent::booted();
        self::addGlobalScopes([
            new IsActiveScope,
            new IsNotYetDeletedScope
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'user_id', 'id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'contact_id', 'id');
    }
}
