<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus nama_lengkap karena sudah ada
            if (!Schema::hasColumn('users', 'id_pegawai')) {
                $table->string('id_pegawai')->nullable();
            }
            if (!Schema::hasColumn('users', 'nip')) {
                $table->string('nip')->nullable();
            }
            if (!Schema::hasColumn('users', 'jabatan')) {
                $table->string('jabatan')->nullable();
            }
            if (!Schema::hasColumn('users', 'pangkat')) {
                $table->string('pangkat')->nullable();
            }
            if (!Schema::hasColumn('users', 'divisi')) {
                $table->string('divisi')->nullable();
            }
            if (!Schema::hasColumn('users', 'foto_profil')) {
                $table->string('foto_profil')->nullable();
            }
            if (!Schema::hasColumn('users', 'tanda_tangan')) {
                $table->string('tanda_tangan')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'id_pegawai', 'nip', 'jabatan', 'pangkat',
                'divisi', 'foto_profil', 'tanda_tangan'
            ]);
        });
    }
};
