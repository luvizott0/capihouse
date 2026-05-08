<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\HashtagFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, \App\Models\Post> $posts
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
class Hashtag extends Model
{
    /** @use HasFactory<HashtagFactory> */
    use HasFactory;

    /**
     * Get posts associated with this hashtag.
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_hashtag');
    }
}
