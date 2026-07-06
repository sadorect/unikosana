<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->unsignedInteger('amount'); // stored in minor units (cents)
            $table->string('currency', 3)->default('usd');
            $table->string('status')->default('pending'); // pending | paid | failed
            $table->string('reference')->nullable(); // Stripe checkout session id
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
