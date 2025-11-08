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
        if (Schema::hasTable('perjanjians')) {
            Schema::table('perjanjians', function (Blueprint $table) {
                if (!Schema::hasColumn('perjanjians', 'pihak1_pangkat')) {
                    $table->string('pihak1_pangkat')->nullable()->after('pihak1_nip');
                }
                if (!Schema::hasColumn('perjanjians', 'pihak2_pangkat')) {
                    $table->string('pihak2_pangkat')->nullable()->after('pihak2_nip');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('perjanjians')) {
            Schema::table('perjanjians', function (Blueprint $table) {
                if (Schema::hasColumn('perjanjians', 'pihak1_pangkat')) {
                    $table->dropColumn('pihak1_pangkat');
                }
                if (Schema::hasColumn('perjanjians', 'pihak2_pangkat')) {
                    $table->dropColumn('pihak2_pangkat');
                }
            });
        }
    }
};
