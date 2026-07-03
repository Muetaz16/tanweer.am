<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $map = [
            'libya' => 'reports',
            'economy' => 'reports',
            'sports' => 'reports',
            'files' => 'investigation',
            'articles' => 'article',
            'varieties' => 'dialogues',
            'video' => 'reels',
        ];

        foreach ($map as $old => $new) {
            DB::table('posts')->where('type', $old)->update(['type' => $new]);
        }
    }

    public function down(): void
    {
        $map = [
            'reports' => 'libya',
            'investigation' => 'files',
            'article' => 'articles',
            'dialogues' => 'varieties',
            'reels' => 'video',
            'podcast' => 'video',
        ];

        foreach ($map as $new => $old) {
            DB::table('posts')->where('type', $new)->update(['type' => $old]);
        }
    }
};
