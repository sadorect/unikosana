<?php

namespace Tests\Feature;

use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Models\Post;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Tests\TestCase;

class PostFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Gate::before(fn () => true);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs(User::factory()->create());
    }

    public function test_the_post_form_saves_and_reloads_tags(): void
    {
        Livewire::test(CreatePost::class)
            ->fillForm(['title' => 'Reunion dates', 'slug' => 'reunion-dates', 'content' => '<p>Soon</p>', 'body' => '<p>Soon</p>', 'tags' => ['reunion', 'houston']])
            ->call('create')
            ->assertHasNoFormErrors();

        $post = Post::firstOrFail();
        $this->assertEqualsCanonicalizing(['reunion', 'houston'], $post->tags->pluck('name')->all());

        Livewire::test(EditPost::class, ['record' => $post->getRouteKey()])
            ->assertFormSet(fn (array $state) => $this->assertEqualsCanonicalizing(['reunion', 'houston'], $state['tags']) ?? []);
    }
}
