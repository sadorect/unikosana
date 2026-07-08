<?php

namespace App\Models;

use App\Enums\MemberStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Member extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'full_name',
        'membership_id',
        'state_province',
        'country',
        'occupation',
        'school',
        'graduation_year',
        'biography',
        'contact_email',
        'contact_phone',
        'avatar_url',
        'is_public',
        'user_id',
        'status',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'graduation_year' => 'integer',
        'status' => MemberStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true)
            ->where('status', MemberStatus::Approved->value);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(\Spatie\Image\Enums\Fit::Crop, 400, 400)
            ->nonQueued();
    }

    public function getPhotoUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('photo');

        // Prefer a locally uploaded photo; otherwise fall back to the avatar
        // synced from the national (Unikosa) profile.
        return $media ? $media->getUrl() : ($this->avatar_url ?: null);
    }
}
