<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $color
 * @property string $emoji
 * @property string|null $deleted_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @property-read int|null $posts_count
 * @method static \Database\Factories\FeelingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feeling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feeling newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feeling query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feeling whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feeling whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feeling whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feeling whereEmoji($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feeling whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feeling whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feeling whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[Fillable(['name', 'color', 'emoji'])]
class Feeling extends Model
{
    /** @use HasFactory<\Database\Factories\FeelingFactory> */
    use HasFactory;

    /**
     * Get posts associated with this feeling.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
