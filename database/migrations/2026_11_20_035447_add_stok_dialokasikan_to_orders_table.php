<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('stok_dialokasikan')->default(0)->after('jumlah');
        });

        // pesanan lama: seluruh jumlahnya dulu dialokasikan dari stok
        DB::table('orders')->update(['stok_dialokasikan' => DB::raw('jumlah')]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('stok_dialokasikan');
        });
    }
};
