<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Change the enum to a plain string so future role additions don't
        // require schema changes. The App\Enums\UserRole cast on the User
        // model keeps the set of valid values enforced at the app layer.
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 32)->change();
        });

        // Rename legacy role value
        DB::table('users')->where('role', 'jae_staff')->update(['role' => 'hr_admin']);

        // Promote the platform owner to super admin
        DB::table('users')
            ->where('email', 'admin@seekrecruit.com')
            ->update(['role' => 'super_admin']);
    }

    public function down(): void
    {
        // Revert value changes first
        DB::table('users')
            ->where('role', 'super_admin')
            ->update(['role' => 'jae_staff']);

        DB::table('users')
            ->where('role', 'hr_admin')
            ->update(['role' => 'jae_staff']);

        // Then restore the enum constraint
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['candidate', 'jae_staff'])->change();
        });
    }
};
