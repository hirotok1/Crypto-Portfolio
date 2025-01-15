<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('coin');
            $table->string('placea');
            $table->decimal('amounta', 16, 8);
            $table->string('placeb');
            $table->decimal('amountb', 16, 8);
            $table->string('customfeecoin')->nullable();
            $table->decimal('customfee', 16, 8)->default(0);
            $table->timestamp('customtime')->nullable();
            $table->string('memo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sends');
    }
};
