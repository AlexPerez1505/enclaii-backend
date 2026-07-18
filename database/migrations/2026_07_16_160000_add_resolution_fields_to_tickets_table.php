<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('resolution_type')->nullable()->after('status');
            $table->text('resolution_summary')->nullable()->after('resolution_type');
            $table->text('client_message')->nullable()->after('resolution_summary');
            $table->string('evidence_path')->nullable()->after('client_message');
            $table->unsignedBigInteger('resolved_by')->nullable()->after('evidence_path');
            $table->timestamp('resolved_at')->nullable()->after('resolved_by');

            $table->foreign('resolved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['resolved_by']);
            $table->dropColumn([
                'resolution_type', 'resolution_summary', 'client_message',
                'evidence_path', 'resolved_by', 'resolved_at',
            ]);
        });
    }
};
