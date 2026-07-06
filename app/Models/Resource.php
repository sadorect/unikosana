<?php

namespace App\Models;

use App\Enums\ResourceCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Resource extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'category',
        'description',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'category' => ResourceCategory::class,
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file')->singleFile();
    }

    public function getFileUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('file');

        return $media ? $media->getUrl() : null;
    }

    public function getFileSizeAttribute(): ?string
    {
        $media = $this->getFirstMedia('file');

        return $media ? $media->humanReadableSize : null;
    }
}
