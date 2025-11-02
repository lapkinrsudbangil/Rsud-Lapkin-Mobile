<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('perjanjians', function (Blueprint $table) {
            $table->text('sasaran')->nullable()->after('deskripsi');
            $table->decimal('bobot', 5, 2)->nullable()->after('sasaran');
            $table->string('sumber_data')->nullable()->after('bobot');
            $table->string('pihak1_name')->nullable()->after('sumber_data');
            $table->string('pihak1_signature')->nullable()->after('pihak1_name');
            $table->string('pihak2_name')->nullable()->after('pihak1_signature');
            $table->string('pihak2_signature')->nullable()->after('pihak2_name');
        });

        Schema::table('laporans', function (Blueprint $table) {
            $table->text('sasaran')->nullable()->after('uraian_kegiatan');
            $table->decimal('bobot', 5, 2)->nullable()->after('sasaran');
            $table->string('sumber_data')->nullable()->after('bobot');
            $table->string('pihak1_name')->nullable()->after('sumber_data');
            $table->string('pihak1_signature')->nullable()->after('pihak1_name');
            $table->string('pihak2_name')->nullable()->after('pihak1_signature');
            $table->string('pihak2_signature')->nullable()->after('pihak2_name');
        });
    }

    public function down()
    {
        Schema::table('perjanjians', function (Blueprint $table) {
            $table->dropColumn(['sasaran','bobot','sumber_data','pihak1_name','pihak1_signature','pihak2_name','pihak2_signature']);
        });

        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn(['sasaran','bobot','sumber_data','pihak1_name','pihak1_signature','pihak2_name','pihak2_signature']);
        });
    }
};
