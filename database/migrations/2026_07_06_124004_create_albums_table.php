<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type')->default('photo');
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('year')->nullable();
            $table->text('description')->nullable();
            $table->json('videos')->nullable();
            $table->timestamps();

            $table->index('year');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
