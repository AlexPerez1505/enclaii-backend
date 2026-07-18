<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patient_registration_links', function (Blueprint $table) {
            $table->string('patient_message', 150)->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('patient_registration_links', function (Blueprint $table) {
            $table->dropColumn('patient_message');
        });
    }
};
