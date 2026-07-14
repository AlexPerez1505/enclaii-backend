<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_conversations', function (Blueprint $table) {
            if (! Schema::hasColumn('ai_conversations', 'mode')) {
                $table->string('mode', 20)->default('bot')->after('status');
            }

            if (! Schema::hasColumn('ai_conversations', 'agent_id')) {
                $table->foreignId('agent_id')->nullable()->after('mode')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('ai_conversations', function (Blueprint $table) {
            if (Schema::hasColumn('ai_conversations', 'agent_id')) {
                $table->dropForeign(['agent_id']);
                $table->dropColumn('agent_id');
            }

            if (Schema::hasColumn('ai_conversations', 'mode')) {
                $table->dropColumn('mode');
            }
        });
    }
};
