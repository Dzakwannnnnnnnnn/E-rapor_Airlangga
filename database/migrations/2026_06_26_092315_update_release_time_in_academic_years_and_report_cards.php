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
        Schema::table('academic_years', function (Blueprint $table) {
            $table->timestamp('report_release_at')->nullable()->after('is_active');
        });

        Schema::table('report_cards', function (Blueprint $table) {
            $table->dropColumn('publish_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_cards', function (Blueprint $table) {
            $table->timestamp('publish_at')->nullable()->after('description');
        });

        Schema::table('academic_years', function (Blueprint $table) {
            $table->dropColumn('report_release_at');
        });
    }
};
