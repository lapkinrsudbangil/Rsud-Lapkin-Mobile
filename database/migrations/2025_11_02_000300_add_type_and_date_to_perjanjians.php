<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('perjanjians', function (Blueprint $table) {
            $table->string('jenis')->nullable()->after('tahun'); // normal | perubahan
            $table->date('tanggal_pembuatan')->nullable()->after('jenis');
            $table->string('change_mode')->nullable()->after('tanggal_pembuatan'); // ubah_target | ubah_perjanjian
        });
    }

    public function down()
    {
        Schema::table('perjanjians', function (Blueprint $table) {
            $table->dropColumn(['jenis','tanggal_pembuatan','change_mode']);
        });
    }
};
