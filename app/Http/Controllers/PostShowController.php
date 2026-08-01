<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class PostShowController extends Controller
{
    public function __invoke(string $slug): View
    {
        $post = Post::query()
            ->with('images')
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Post::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->where('type', $post->type)
            ->latest('published_at')
            ->limit(4)
            ->get();

        return view('posts.show', compact('post', 'related'));
    }
}
