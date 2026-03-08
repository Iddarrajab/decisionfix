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
        Schema::table('diagnosa', function (Blueprint $table) {
            // Tambahkan kolom code setelah id (jika belum ada)
            if (!Schema::hasColumn('diagnosa', 'code')) {
                $table->string('code')->unique()->after('id');
            }
            
            // Tambahkan kolom cf_gejala (JSON) setelah gejala_dipilih (jika belum ada)
            if (!Schema::hasColumn('diagnosa', 'cf_gejala')) {
                $table->json('cf_gejala')->nullable()->after('gejala_dipilih');
            }
            
            // Tambahkan kolom hasil_keputusan setelah hasil_cf (jika belum ada)
            if (!Schema::hasColumn('diagnosa', 'hasil_keputusan')) {
                $table->text('hasil_keputusan')->nullable()->after('hasil_cf');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diagnosa', function (Blueprint $table) {
            $table->dropColumn(['code', 'cf_gejala', 'hasil_keputusan']);
            
            // Kembalikan tipe hasil_cf jika perlu
            if (Schema::hasColumn('diagnosa', 'hasil_cf')) {
                $table->float('hasil_cf', 5, 2)->nullable()->change();
            }
        });
    }
};
