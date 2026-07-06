<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->date('date');
            $table->time('time')->nullable();
            $table->string('venue')->nullable();
            $table->longText('description')->nullable();
            $table->string('status')->default('upcoming');
            $table->string('registration_link')->nullable();
            $table->json('speakers')->nullable();
            $table->json('videos')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index('date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
