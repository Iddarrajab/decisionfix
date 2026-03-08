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
        Schema::create('diagnosa', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('nama_user');
            $table->json('gejala_dipilih'); // simpan dalam bentuk JSON
            $table->json('cf_gejala')->nullable(); // simpan nilai CF user untuk setiap gejala
            $table->foreignId('penyakit_id')->nullable()->constrained('penyakit')->onDelete('set null');
            $table->float('hasil_cf', 8, 4)->nullable();
            $table->text('hasil_keputusan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosa');
    }
};
