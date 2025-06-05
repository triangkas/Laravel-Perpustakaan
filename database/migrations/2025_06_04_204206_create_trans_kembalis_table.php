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
        Schema::create('trans_kembalis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pinjam_id');
            $table->foreign('pinjam_id')->references('id')->on('trans_pinjams')->onUpdate('cascade');
            $table->date('tanggal_kembali');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_kembalis');
    }
};
