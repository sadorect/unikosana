<?php

namespace App\Models;

use App\Enums\AlbumType;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Album extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'event_id',
        'year',
        'description',
        'videos',
    ];

    protected $casts = [
        'type' => AlbumType::class,
        'videos' => 'array',
        'year' => 'integer',
    ];

    public function sluggable(): array
    {
        return ['slug' => ['source' => 'title']];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(\Spatie\Image\Enums\Fit::Crop, 500, 500)
            ->nonQueued();
    }

    public function getCoverUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('photos');

        return $media ? $media->getUrl('thumb') : null;
    }
}
