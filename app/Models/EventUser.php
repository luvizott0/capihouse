<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\EventUserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $event_id
 * @property int $user_id
 * @property string $status
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Event $event
 * @property-read User $user
 *
 * @method static \Database\Factories\EventUserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventUser whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventUser whereUserId($value)
 *
 * @mixin \Eloquent
 */
#[Fillable(['event_id', 'user_id', 'status'])]
class EventUser extends Model
{
    /** @use HasFactory<EventUserFactory> */
    use HasFactory;

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
