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
        if (! Schema::hasTable('promotion_banners')) {
            Schema::create('promotion_banners', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('tag')->nullable();
                $table->string('image_path');
                $table->string('external_link', 500)->nullable();
                $table->integer('sort_order')->default(0)->index();
                $table->string('status', 20)->default('active')->index();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('business_plan_documents')) {
            Schema::create('business_plan_documents', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('language')->default('English');
                $table->string('native_language')->nullable();
                $table->string('flag', 20)->nullable();
                $table->string('flag_code', 20)->nullable();
                $table->string('badge')->nullable();
                $table->string('badge_color', 50)->nullable();
                $table->string('gradient', 255)->nullable();
                $table->string('border_color', 50)->nullable();
                $table->string('icon_color', 50)->nullable();
                $table->string('file_path');
                $table->string('file_size', 50)->nullable();
                $table->string('pages_hint')->nullable();
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(0)->index();
                $table->string('status', 20)->default('active')->index();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('member_videos')) {
            Schema::create('member_videos', function (Blueprint $table) {
                $table->id();
                $table->string('video_type', 30)->index(); // 'plan' or 'tutorial'
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('tag')->nullable();
                $table->string('video_source', 20)->default('upload'); // 'upload' or 'url'
                $table->string('video_file')->nullable();
                $table->string('video_url', 500)->nullable();
                $table->string('thumbnail')->nullable();
                $table->string('duration', 50)->nullable();
                $table->integer('sort_order')->default(0)->index();
                $table->string('status', 20)->default('active')->index();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_videos');
        Schema::dropIfExists('business_plan_documents');
        Schema::dropIfExists('promotion_banners');
    }
};
