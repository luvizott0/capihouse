<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @property-read int|null $posts_count
 * @method static \Database\Factories\hashtagFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|hashtag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|hashtag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|hashtag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|hashtag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|hashtag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|hashtag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|hashtag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[Fillable(['name'])]
class hashtag extends Model
{
    /** @use HasFactory<\Database\Factories\HashtagFactory> */
    use HasFactory;

    /**
     * Get posts associated with this hashtag.
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_hashtag');
    }
}
