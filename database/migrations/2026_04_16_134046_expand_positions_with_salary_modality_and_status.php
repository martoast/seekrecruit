<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->integer('salary_min')->nullable()->after('requirements');
            $table->integer('salary_max')->nullable()->after('salary_min');
            $table->string('salary_currency', 3)->default('USD')->after('salary_max');
            $table->enum('employment_type', ['full_time', 'part_time', 'internship', 'contract'])
                ->default('full_time')
                ->after('salary_currency');
            $table->enum('modality', ['on_site', 'remote', 'hybrid'])
                ->default('on_site')
                ->after('employment_type');
            $table->enum('status', ['open', 'closed', 'draft'])
                ->default('open')
                ->after('modality');
        });

        // Backfill status from the existing is_active boolean
        DB::table('positions')->where('is_active', true)->update(['status' => 'open']);
        DB::table('positions')->where('is_active', false)->update(['status' => 'closed']);

        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('location');
        });

        DB::table('positions')->where('status', 'open')->update(['is_active' => true]);
        DB::table('positions')->whereIn('status', ['closed', 'draft'])->update(['is_active' => false]);

        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn([
                'salary_min',
                'salary_max',
                'salary_currency',
                'employment_type',
                'modality',
                'status',
            ]);
        });
    }
};
