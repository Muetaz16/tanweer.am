<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@tanweer.local'],
            [
                'name' => 'مدير تنوير',
                'password' => '123456789',
                'is_admin' => true,
            ]
        );

        if (Post::query()->where('slug', 'welcome-tanweer')->exists()) {
            return;
        }

        Post::create([
            'user_id' => $admin->id,
            'type' => Post::TYPE_REPORTS,
            'title' => 'مرحباً بكم في منصة تنوير',
            'slug' => 'welcome-tanweer',
            'excerpt' => 'صحيفة إلكترونية للأخبار والمقالات والتقارير والفيديو.',
            'body' => "هذه تجربة أولى لمحتوى منشور على الموقع.\n\nيمكن للمدير إضافة الأخبار والمقالات من لوحة التحكم.",
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
