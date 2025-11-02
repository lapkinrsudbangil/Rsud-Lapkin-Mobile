<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('perjanjian_id')->nullable()->constrained('perjanjians')->onDelete('set null');
            $table->string('periode_awal')->nullable();
            $table->string('periode_akhir')->nullable();
            $table->text('uraian_kegiatan')->nullable();
            $table->json('indikator')->nullable();
            $table->decimal('target', 10, 2)->nullable();
            $table->decimal('realisasi', 10, 2)->nullable();
            $table->decimal('persentase', 5, 2)->nullable();
            $table->string('satuan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporans');
    }
};
