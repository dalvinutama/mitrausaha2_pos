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
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('conversation_id')->nullable()->constrained()->onDelete('cascade')->after('id');
            $table->enum('type', ['text', 'image', 'document', 'audio', 'video', 'system'])->default('text')->after('content');
            $table->foreignId('reply_to_id')->nullable()->constrained('messages')->onDelete('set null')->after('type');
            $table->boolean('is_pinned')->default(false)->after('reply_to_id');
            
            // To support old global chat messages during transition, conversation_id is nullable for now.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['conversation_id']);
            $table->dropForeign(['reply_to_id']);
            $table->dropColumn(['conversation_id', 'type', 'reply_to_id', 'is_pinned']);
        });
    }
};
