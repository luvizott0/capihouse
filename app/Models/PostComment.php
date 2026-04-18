<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $post_id
 * @property int $user_id
 * @property string $content
 * @property \Carbon\CarbonImmutable|null $deleted_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Post $post
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\PostCommentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment withoutTrashed()
 * @mixin \Eloquent
 */
#[Fillable(['post_id', 'user_id', 'content'])]
class PostComment extends Model
{
    /** @use HasFactory<\Database\Factories\PostCommentFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * Get post related to this comment.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get user that created this comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
