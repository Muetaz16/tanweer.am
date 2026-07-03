<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Post extends Model
{
    public const TYPE_NEWS = 'news';
    public const TYPE_REPORTS = 'reports';
    public const TYPE_INVESTIGATION = 'investigation';
    public const TYPE_ARTICLE = 'article';
    public const TYPE_INFOGRAPHICS = 'infographics';
    public const TYPE_REELS = 'reels';
    public const TYPE_DIALOGUES = 'dialogues';
    public const TYPE_PODCAST = 'podcast';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'slug',
        'excerpt',
        'body',
        'cover_image',
        'video_url',
        'youtube_video_id',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public static function types(): array
    {
        return [
            self::TYPE_NEWS => 'الأخبار',
            self::TYPE_REPORTS => 'تقارير',
            self::TYPE_INVESTIGATION => 'تحقيقات',
            self::TYPE_ARTICLE => 'مقالات',
            self::TYPE_INFOGRAPHICS => 'انفوجرافيك',
            self::TYPE_REELS => 'ريلز',
            self::TYPE_DIALOGUES => 'حوارات',
            self::TYPE_PODCAST => 'بودكاست',
        ];
    }

    public static function navPrimaryLinks(): array
    {
        return [
            ['key' => 'home', 'label' => 'الرئيسية', 'href' => route('home')],
            ['key' => self::TYPE_NEWS, 'label' => 'الأخبار', 'href' => route('category.show', self::TYPE_NEWS)],
            ['key' => self::TYPE_REPORTS, 'label' => 'تقارير', 'href' => route('category.show', self::TYPE_REPORTS)],
            ['key' => self::TYPE_INVESTIGATION, 'label' => 'تحقيقات', 'href' => route('category.show', self::TYPE_INVESTIGATION)],
            ['key' => self::TYPE_ARTICLE, 'label' => 'مقالات', 'href' => route('category.show', self::TYPE_ARTICLE)],
            ['key' => self::TYPE_INFOGRAPHICS, 'label' => 'انفوجرافيك', 'href' => route('category.show', self::TYPE_INFOGRAPHICS)],
        ];
    }

    public static function navVideoLinks(): array
    {
        return [
            ['key' => self::TYPE_REELS, 'label' => 'ريلز', 'href' => route('category.show', self::TYPE_REELS)],
            ['key' => self::TYPE_DIALOGUES, 'label' => 'الحوارات', 'href' => route('category.show', self::TYPE_DIALOGUES)],
            ['key' => self::TYPE_PODCAST, 'label' => 'البودكاست', 'href' => route('category.show', self::TYPE_PODCAST)],
        ];
    }

    /** @return list<string> */
    public static function navVideoTypes(): array
    {
        return [self::TYPE_REELS, self::TYPE_DIALOGUES, self::TYPE_PODCAST];
    }

    public static function isNavVideoType(?string $type): bool
    {
        return $type !== null && in_array($type, self::navVideoTypes(), true);
    }

    public static function videoTypes(): array
    {
        return self::navVideoTypes();
    }

    public function supportsVideo(): bool
    {
        return in_array($this->type, self::videoTypes(), true);
    }

    public function typeLabel(): string
    {
        return self::types()[$this->type] ?? $this->type;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function videoEmbedSrc(): ?string
    {
        if ($this->youtube_video_id) {
            return 'https://www.youtube.com/embed/' . $this->youtube_video_id;
        }

        return $this->video_url;
    }

    public static function extractYoutubeId(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $url, $m)) {
            return $m[1];
        }

        return null;
    }

    public function isYoutubeShorts(): bool
    {
        return $this->video_url && str_contains(strtolower($this->video_url), '/shorts/');
    }

    public function thumbnailUrl(): ?string
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }

        if ($this->youtube_video_id) {
            return 'https://img.youtube.com/vi/' . $this->youtube_video_id . '/maxresdefault.jpg';
        }

        return null;
    }

    public static function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title, '-', 'ar');
        if ($base === '') {
            $base = 'post-'.Str::lower(Str::random(10));
        }

        $slug = $base;
        $n = 1;
        while (
            static::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base.'-'.$n++;
        }

        return $slug;
    }
}
