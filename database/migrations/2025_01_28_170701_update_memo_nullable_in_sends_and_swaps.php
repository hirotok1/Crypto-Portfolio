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
        // sendsテーブルのmemoをnullableに変更
        Schema::table('sends', function (Blueprint $table) {
            $table->string('memo')->nullable()->change();
        });

        // swapsテーブルのmemoをnullableに変更
        Schema::table('swaps', function (Blueprint $table) {
            $table->string('memo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // sendsテーブルのmemoを元に戻す
        Schema::table('sends', function (Blueprint $table) {
            $table->string('memo')->nullable(false)->change();
        });

        // swapsテーブルのmemoを元に戻す
        Schema::table('swaps', function (Blueprint $table) {
            $table->string('memo')->nullable(false)->change();
        });
    }
};
