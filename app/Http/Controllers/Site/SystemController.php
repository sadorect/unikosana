<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Event;
use App\Models\Post;
use App\Settings\ThemeSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SystemController extends Controller
{
    /**
     * Static, always-indexable marketing routes and their change cadence.
     *
     * @var array<string, array{0: string, 1: float}>  route name => [changefreq, priority]
     */
    protected array $staticRoutes = [
        'home' => ['daily', 1.0],
        'about' => ['monthly', 0.8],
        'leadership' => ['monthly', 0.6],
        'members' => ['weekly', 0.5],
        'events.index' => ['daily', 0.9],
        'blog' => ['weekly', 0.7],
        'news.index' => ['daily', 0.8],
        'gallery.index' => ['weekly', 0.6],
        'resources' => ['weekly', 0.6],
        'faq' => ['monthly', 0.5],
        'donate' => ['monthly', 0.7],
        'contact' => ['yearly', 0.5],
    ];

    /**
     * XML sitemap of all public, indexable URLs.
     */
    public function sitemap(): Response
    {
        $urls = [];

        foreach ($this->staticRoutes as $name => [$changefreq, $priority]) {
            if (! app('router')->has($name)) {
                continue;
            }
            $urls[] = [
                'loc' => route($name),
                'changefreq' => $changefreq,
                'priority' => $priority,
            ];
        }

        // Detail pages for content that can be crawled and shared.
        $urls = array_merge(
            $urls,
            $this->modelUrls(fn () => Post::published()->get(), 'news.show', 0.7),
            $this->modelUrls(fn () => Event::query()->whereNotNull('slug')->get(), 'events.show', 0.6),
            $this->modelUrls(fn () => Album::query()->get(), 'gallery.show', 0.5),
        );

        $xml = view('site.sitemap', ['urls' => $urls])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Safely resolve detail URLs for a content model, ignoring any that
     * cannot be routed (missing slug, migration not yet run, etc.).
     *
     * @param  callable():\Illuminate\Support\Collection  $query
     * @return array<int, array{loc: string, changefreq: string, priority: float, lastmod?: string}>
     */
    protected function modelUrls(callable $query, string $routeName, float $priority): array
    {
        $urls = [];

        try {
            foreach ($query() as $model) {
                $entry = [
                    'loc' => route($routeName, $model),
                    'changefreq' => 'weekly',
                    'priority' => $priority,
                ];

                if ($model->updated_at instanceof Carbon) {
                    $entry['lastmod'] = $model->updated_at->toAtomString();
                }

                $urls[] = $entry;
            }
        } catch (\Throwable $e) {
            // Table not migrated / route missing — skip this content type.
        }

        return $urls;
    }

    /**
     * Dynamic web app manifest, branded from ThemeSettings.
     */
    public function manifest(ThemeSettings $theme): JsonResponse
    {
        // A short_name of ~12 chars max is used on the home screen; prefer a
        // clean word boundary over a hard character cut ("Unikosa Nort").
        $shortName = \Illuminate\Support\Str::of($theme->site_name)->words(2, '')->trim();
        if ($shortName->length() > 12) {
            $shortName = $shortName->words(1, '');
        }

        $manifest = [
            'name' => $theme->site_name,
            'short_name' => (string) $shortName,
            'description' => 'Official website of the North America Unit of Unikosa — events, members, news and resources.',
            'start_url' => '/?source=pwa',
            'scope' => '/',
            'display' => 'standalone',
            'orientation' => 'portrait-primary',
            'background_color' => '#f8fafc',
            'theme_color' => $theme->primary_color,
            'lang' => 'en-US',
            'categories' => ['education', 'social', 'nonprofit'],
            'icons' => [
                ['src' => asset('icons/icon-192.png'), 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any'],
                ['src' => asset('icons/icon-512.png'), 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any'],
                ['src' => asset('icons/icon-maskable-512.png'), 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'maskable'],
            ],
            'shortcuts' => [
                ['name' => 'Events', 'url' => '/events'],
                ['name' => 'News', 'url' => '/news'],
                ['name' => 'Donate', 'url' => '/donate'],
            ],
        ];

        return response()->json($manifest, 200, [
            'Content-Type' => 'application/manifest+json',
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Offline fallback served by the service worker.
     */
    public function offline()
    {
        return response()->view('site.offline');
    }
}
