<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('ai_conversations', 'title')) {
                $table->string('title')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('ai_conversations', 'status')) {
                $table->string('status')->default('closed')->after('title');
            }

            if (!Schema::hasColumn('ai_conversations', 'last_message_at')) {
                $table->timestamp('last_message_at')->nullable()->after('status');
            }

            if (!Schema::hasColumn('ai_conversations', 'closed_at')) {
                $table->timestamp('closed_at')->nullable()->after('last_message_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ai_conversations', function (Blueprint $table) {
            if (Schema::hasColumn('ai_conversations', 'closed_at')) {
                $table->dropColumn('closed_at');
            }

            if (Schema::hasColumn('ai_conversations', 'last_message_at')) {
                $table->dropColumn('last_message_at');
            }

            if (Schema::hasColumn('ai_conversations', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('ai_conversations', 'title')) {
                $table->dropColumn('title');
            }
        });
    }
};