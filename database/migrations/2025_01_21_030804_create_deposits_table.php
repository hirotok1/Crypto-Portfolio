<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ユーザーとのリレーション
            $table->string('place'); // 入金先（取引所、銀行、ウォレットなど）
            $table->decimal('amount', 20, 8); // 金額
            $table->timestamp('customtime'); // 入金日時
            $table->text('memo')->nullable(); // メモ（任意）
            $table->timestamps(); // 作成・更新日時
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
