<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('author_name')->nullable()->after('user_id');
            $table->string('external_url', 2048)->nullable()->after('video_url');
            $table->json('investigation_persons')->nullable()->after('content_blocks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['author_name', 'external_url', 'investigation_persons']);
        });
    }
};
