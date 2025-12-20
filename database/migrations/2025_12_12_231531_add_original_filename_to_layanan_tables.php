<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambah kolom original_filename ke tabel banks
        Schema::table('banks', function (Blueprint $table) {
            $table->string('original_filename')->nullable()->after('file_path');
        });

        // Tambah kolom original_filename ke tabel veras
        Schema::table('veras', function (Blueprint $table) {
            $table->string('original_filename')->nullable()->after('file_path');
        });

        // Tambah kolom original_filename ke tabel pds
        Schema::table('pds', function (Blueprint $table) {
            $table->string('original_filename')->nullable()->after('file_path');
        });

        // Tambah kolom original_filename ke tabel mskis
        Schema::table('mskis', function (Blueprint $table) {
            $table->string('original_filename')->nullable()->after('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn('original_filename');
        });

        Schema::table('veras', function (Blueprint $table) {
            $table->dropColumn('original_filename');
        });

        Schema::table('pds', function (Blueprint $table) {
            $table->dropColumn('original_filename');
        });

        Schema::table('mskis', function (Blueprint $table) {
            $table->dropColumn('original_filename');
        });
    }
};