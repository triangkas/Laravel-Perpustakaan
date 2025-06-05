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
        Schema::create('trans_pinjam_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pinjam_id');
            $table->foreign('pinjam_id')->references('id')->on('trans_pinjams')->onUpdate('cascade');
            $table->uuid('buku_id');
            $table->foreign('buku_id')->references('id')->on('base_bukus')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_pinjam_items');
    }
};
