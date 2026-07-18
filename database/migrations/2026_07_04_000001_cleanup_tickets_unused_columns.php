<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'tax_address',
                'rfc',
                'operation_datetime',
                'concepts',
                'subtotal',
                'tax',
                'total',
                'totals',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('tax_address')->nullable()->after('business_name');
            $table->string('rfc')->nullable()->after('tax_address');
            $table->string('operation_datetime')->nullable()->after('operation_folio');
            $table->text('concepts')->nullable()->after('operation_datetime');
            $table->decimal('subtotal', 12, 2)->nullable()->after('concepts');
            $table->decimal('tax', 12, 2)->nullable()->after('subtotal');
            $table->decimal('total', 12, 2)->nullable()->after('tax');
            $table->string('totals')->nullable()->after('payment_method');
        });
    }
};
