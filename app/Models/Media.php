<?php

namespace App\Models;

use App\Enums\MediaType;
use Carbon\CarbonImmutable;
use Database\Factories\MediaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $path
 * @property MediaType $type
 * @property string|null $collection_name
 * @property string $mediable_type
 * @property int $mediable_id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Model|\Eloquent $mediable
 *
 * @method static \Database\Factories\MediaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereCollectionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereMediableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereMediableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[Fillable(['path', 'type', 'collection_name', 'mediable_id', 'mediable_type'])]
class Media extends Model
{
    /** @use HasFactory<MediaFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => MediaType::class,
        ];
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrl(): string
    {
        return $this->path;
    }
}
