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
        Schema::create('decision_nodes', function (Blueprint $table) {
            $table->id();
            $table->string('node_code', 20)->unique();
            $table->foreignId('gejala_id')->nullable()->constrained('gejala')->onDelete('set null');
            $table->foreignId('penyakit_id')->nullable()->constrained('penyakit')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('decision_nodes')->onDelete('cascade');
            $table->string('yes_branch')->nullable(); // arah cabang "ya"
            $table->string('no_branch')->nullable();  // arah cabang "tidak"
            $table->boolean('is_leaf')->default(false); // true jika node akhir
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decision_nodes');
    }
};
