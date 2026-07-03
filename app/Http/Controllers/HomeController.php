<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        // 4 recent posts: 1 main + 3 side
        $featured = Post::query()
            ->published()
            ->latest('published_at')
            ->limit(4)
            ->get();

        // Specific category queries
        $reportsPosts = Post::query()->published()->where('type', Post::TYPE_REPORTS)->latest('published_at')->limit(6)->get();
        $investigationPosts = Post::query()->published()->where('type', Post::TYPE_INVESTIGATION)->latest('published_at')->limit(4)->get();
        $articlePosts = Post::query()->published()->where('type', Post::TYPE_ARTICLE)->latest('published_at')->limit(4)->get();
        $reelsPosts = Post::query()->published()->where('type', Post::TYPE_REELS)->latest('published_at')->limit(4)->get();
        $latestNews = Post::query()->published()->latest('published_at')->limit(10)->get();

        return view('home', compact(
            'featured',
            'reportsPosts',
            'investigationPosts',
            'articlePosts',
            'reelsPosts',
            'latestNews'
        ));
    }
}
