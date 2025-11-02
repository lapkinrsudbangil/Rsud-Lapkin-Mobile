<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('perjanjians', function (Blueprint $table) {
            $table->id();
            $table->string('jabatan')->index();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->json('indikator')->nullable();
            $table->year('tahun')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('perjanjians');
    }
};
