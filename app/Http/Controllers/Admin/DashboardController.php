<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $counts = collect(Post::types())
            ->mapWithKeys(fn (string $label, string $type) => [$type => Post::where('type', $type)->count()])
            ->all();

        $counts['published'] = Post::where('is_published', true)->count();
        $counts['draft'] = Post::where('is_published', false)->count();

        $recent = Post::query()
            ->with('user')
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact('counts', 'recent'));
    }
}
