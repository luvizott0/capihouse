<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $post_id
 * @property int $user_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Post $post
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostLike query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostLike wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostLike whereUserId($value)
 * @mixin \Eloquent
 */
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
