<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type')->default('news');
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('category')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index('type');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
