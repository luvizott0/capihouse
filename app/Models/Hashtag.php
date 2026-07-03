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
 * @method static \Database\Factories\HashtagFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hashtag whereUpdatedAt($value)
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
