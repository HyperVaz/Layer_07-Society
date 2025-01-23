<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avatar extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'filename', 'path', 'disk',
    ];

    /**
     * Get the user that owns the avatar.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // Связь принадлежит к модели User
    }
}
