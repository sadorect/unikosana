<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('membership_id')->nullable();
            $table->string('state_province')->nullable();
            $table->string('country')->default('United States');
            $table->string('occupation')->nullable();
            $table->string('school')->nullable();
            $table->unsignedSmallInteger('graduation_year')->nullable();
            $table->text('biography')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();

            $table->index('country');
            $table->index('state_province');
            $table->index('graduation_year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
