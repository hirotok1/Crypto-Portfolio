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
        Schema::create('changes', function (Blueprint $table) {
            $table->id(); // 主キー
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ユーザーとのリレーション
            $table->string('place'); // 場所 (例: Bitbank, HWW, 銀行など)
            $table->string('coin'); // コイン名 (例: BTC, ETH)
            $table->decimal('change', 16, 8); // 残高の変化量 (+/-)
            $table->string('related_type')->nullable(); // 紐づく取引の種類 (例: 'swaps', 'sends', 'deposits')
            $table->unsignedBigInteger('related_id')->nullable(); // 紐づく取引のID (swaps, sends, deposits など)
            $table->timestamp('customtime')->nullable(); // ユーザーが指定した日時
            $table->timestamps(); // 作成日時と更新日時
            $table->softDeletes(); // ソフトデリート用 deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('changes');
    }
};
