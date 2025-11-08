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
                if (!Schema::hasColumn('perjanjians', 'pihak1_nip')) {
                    $table->string('pihak1_nip')->nullable()->after('pihak1_jabatan');
                }
                if (!Schema::hasColumn('perjanjians', 'pihak2_nip')) {
                    $table->string('pihak2_nip')->nullable()->after('pihak2_jabatan');
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
                if (Schema::hasColumn('perjanjians', 'pihak1_nip')) {
                    $table->dropColumn('pihak1_nip');
                }
                if (Schema::hasColumn('perjanjians', 'pihak2_nip')) {
                    $table->dropColumn('pihak2_nip');
                }
            });
        }
    }
};
