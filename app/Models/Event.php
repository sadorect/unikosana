<?php

namespace App\Models;

use App\Enums\EventStatus;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Event extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'date',
        'time',
        'venue',
        'description',
        'status',
        'registration_link',
        'registration_enabled',
        'capacity',
        'speakers',
        'videos',
        'live_stream_url',
        'is_live',
        'is_featured',
    ];

    protected $casts = [
        'date' => 'date',
        'status' => EventStatus::class,
        'speakers' => 'array',
        'videos' => 'array',
        'is_featured' => 'boolean',
        'registration_enabled' => 'boolean',
        'capacity' => 'integer',
        'is_live' => 'boolean',
    ];

    public function registrations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function isFull(): bool
    {
        if (! $this->capacity) {
            return false;
        }

        return $this->registrations()->sum('guests') + $this->registrations()->count() >= $this->capacity;
    }

    public function sluggable(): array
    {
        return ['slug' => ['source' => 'title']];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', '!=', EventStatus::Completed->value)
            ->orderBy('date');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', EventStatus::Completed->value)
            ->orderByDesc('date');
    }

    public function getYearAttribute(): int
    {
        return (int) $this->date->format('Y');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('flyer')->singleFile();
        $this->addMediaCollection('gallery');
        $this->addMediaCollection('documents');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(\Spatie\Image\Enums\Fit::Crop, 600, 400)
            ->nonQueued()
            ->performOnCollections('flyer', 'gallery');
    }

    public function getFlyerUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('flyer');

        return $media ? $media->getUrl() : null;
    }
}
