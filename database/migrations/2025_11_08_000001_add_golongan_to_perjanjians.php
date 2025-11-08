<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('perjanjians', function (Blueprint $table) {
            if (!Schema::hasColumn('perjanjians', 'pihak1_golongan')) {
                $table->string('pihak1_golongan')->nullable()->after('pihak1_pangkat');
            }
            if (!Schema::hasColumn('perjanjians', 'pihak2_golongan')) {
                $table->string('pihak2_golongan')->nullable()->after('pihak2_pangkat');
            }
        });
    }

    public function down()
    {
        Schema::table('perjanjians', function (Blueprint $table) {
            if (Schema::hasColumn('perjanjians', 'pihak1_golongan')) {
                $table->dropColumn('pihak1_golongan');
            }
            if (Schema::hasColumn('perjanjians', 'pihak2_golongan')) {
                $table->dropColumn('pihak2_golongan');
            }
        });
    }
};
