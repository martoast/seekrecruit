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
        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('university');
            $table->string('degree');
            $table->integer('semester')->nullable();
            $table->integer('graduation_year')->nullable();
            $table->json('skills')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('location');
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say']);
            $table->string('phone')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();

            $table->index('university');
            $table->index('degree');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_profiles');
    }
};
