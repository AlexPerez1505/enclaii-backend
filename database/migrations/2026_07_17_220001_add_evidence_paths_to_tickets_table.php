<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->json('evidence_paths')->nullable()->after('evidence_path');
        });

        DB::table('tickets')->whereNotNull('evidence_path')->eachById(function ($row) {
            DB::table('tickets')->where('id', $row->id)->update([
                'evidence_paths' => json_encode([$row->evidence_path]),
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('evidence_paths');
        });
    }
};
