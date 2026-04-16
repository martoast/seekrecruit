<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create clients table
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('industry')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Ensure a default "JAE Tijuana" client exists so existing positions
        //    have somewhere to land. Use the current timestamp for created_at/updated_at.
        $defaultClientId = DB::table('clients')->where('slug', 'jae-tijuana')->value('id');

        if (! $defaultClientId) {
            $defaultClientId = DB::table('clients')->insertGetId([
                'name' => 'JAE Tijuana',
                'slug' => 'jae-tijuana',
                'industry' => 'Manufacturing',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Add client_id to users (nullable — candidates + super admins have null)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('client_id')
                ->nullable()
                ->after('role')
                ->constrained()
                ->nullOnDelete();
        });

        // Assign existing jae_staff users (except admin@seekrecruit.com who will
        // become super admin in the next migration) to the default client.
        DB::table('users')
            ->where('role', 'jae_staff')
            ->where('email', '!=', 'admin@seekrecruit.com')
            ->update(['client_id' => $defaultClientId]);

        // 4. Add client_id to positions — nullable first, backfill, then non-nullable.
        Schema::table('positions', function (Blueprint $table) {
            $table->foreignId('client_id')
                ->nullable()
                ->after('id')
                ->constrained();
        });

        DB::table('positions')
            ->whereNull('client_id')
            ->update(['client_id' => $defaultClientId]);

        Schema::table('positions', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('client_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('client_id');
        });

        Schema::dropIfExists('clients');
    }
};
