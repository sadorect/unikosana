<?php

namespace App\Models;

use App\Enums\PostType;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use HasTags;
    use InteractsWithMedia;
    use Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'author_id',
        'category',
        'excerpt',
        'body',
        'meta_description',
        'published_at',
        'is_featured',
    ];

    protected $casts = [
        'type' => PostType::class,
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function sluggable(): array
    {
        return ['slug' => ['source' => 'title']];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(\Spatie\Image\Enums\Fit::Crop, 800, 450)
            ->nonQueued();
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('featured_image');

        return $media ? $media->getUrl() : null;
    }
}
