<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\InterestFactory;
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
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 *
 * @method static \Database\Factories\InterestFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interest whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interest whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[Fillable(['name'])]
class Interest extends Model
{
    /** @use HasFactory<InterestFactory> */
    use HasFactory;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
