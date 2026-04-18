<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['post_id', 'user_id'])]
class PostLike extends Model
{
    /**
     * Get post related to this like.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get user that created this like.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
