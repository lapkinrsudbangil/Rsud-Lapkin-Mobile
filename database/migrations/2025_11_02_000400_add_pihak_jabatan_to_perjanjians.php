<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('perjanjians', function (Blueprint $table) {
            if (!Schema::hasColumn('perjanjians', 'pihak1_jabatan')) {
                $table->string('pihak1_jabatan')->nullable()->after('pihak1_name');
            }
            if (!Schema::hasColumn('perjanjians', 'pihak2_jabatan')) {
                $table->string('pihak2_jabatan')->nullable()->after('pihak2_name');
            }
        });
    }

    public function down()
    {
        Schema::table('perjanjians', function (Blueprint $table) {
            $table->dropColumn(['pihak1_jabatan', 'pihak2_jabatan']);
        });
    }
};
