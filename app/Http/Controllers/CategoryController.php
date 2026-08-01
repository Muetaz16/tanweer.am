<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(string $type): View
    {
        $types = Post::types();

        if (!array_key_exists($type, $types)) {
            abort(404);
        }

        $categoryName = $types[$type];

        $posts = Post::query()
            ->with('images')
            ->published()
            ->where('type', $type)
            ->latest('published_at')
            ->paginate(12);

        return view('category', compact('posts', 'categoryName', 'type'));
    }
}
