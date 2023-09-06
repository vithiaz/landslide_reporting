<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sended_reports', function (Blueprint $table) {
            $table->foreignId("report")
                    ->constrained(
                        table: 'reports', indexName: 'sended_report_id'
                    )->onUpdate('cascade')->onDelete('cascade');
                
            $table->foreignId("telegram_user")
                    ->constrained(
                        table: 'telegram_users', indexName: 'sended_telegram_user_id'
                    )->onUpdate('cascade')->onDelete('cascade');            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sended_reports', function (Blueprint $table) {
            $table->dropForeign('report');
            $table->dropForeign('telegram_user');
        });
    }
};
