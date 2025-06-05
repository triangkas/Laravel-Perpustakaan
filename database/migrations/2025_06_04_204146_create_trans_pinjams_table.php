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
        Schema::create('trans_pinjams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('tanggal_pinjam');
            $table->uuid('anggota_id');
            $table->foreign('anggota_id')->references('id')->on('base_anggotas')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_pinjams');
    }
};
