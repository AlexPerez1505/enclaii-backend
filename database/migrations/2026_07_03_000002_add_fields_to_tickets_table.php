<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('priority')->nullable()->after('description');
            $table->string('business_name')->nullable()->after('priority');
            $table->string('tax_address')->nullable()->after('business_name');
            $table->string('rfc')->nullable()->after('tax_address');
            $table->string('operation_folio')->nullable()->after('rfc');
            $table->string('operation_datetime')->nullable()->after('operation_folio');
            $table->text('concepts')->nullable()->after('operation_datetime');
            $table->decimal('subtotal', 12, 2)->nullable()->after('concepts');
            $table->decimal('tax', 12, 2)->nullable()->after('subtotal');
            $table->decimal('total', 12, 2)->nullable()->after('tax');
            $table->string('payment_method')->nullable()->after('total');
            $table->string('totals')->nullable()->after('payment_method');
            $table->string('attachment_path')->nullable()->after('totals');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'priority', 'business_name', 'tax_address', 'rfc',
                'operation_folio', 'operation_datetime', 'concepts',
                'subtotal', 'tax', 'total', 'payment_method', 'totals', 'attachment_path',
            ]);
        });
    }
};
