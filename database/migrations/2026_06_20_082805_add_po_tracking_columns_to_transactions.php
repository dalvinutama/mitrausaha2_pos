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
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('po_id')->nullable()->after('supplier_id');
            $table->foreign('po_id')->references('id')->on('transactions')->onDelete('set null');
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->integer('qty_diterima')->default(0)->after('qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['po_id']);
            $table->dropColumn('po_id');
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropColumn('qty_diterima');
        });
    }
};
