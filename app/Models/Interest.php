<?php

namespace App\Models;

use Database\Factories\InterestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\InterestFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interest whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interest whereUpdatedAt($value)
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
