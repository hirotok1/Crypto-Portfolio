<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        /*
        Schema::table('places', function (Blueprint $table) {
            // user_idカラムを一旦削除して再追加することで順序を変更
            $table->dropColumn('user_id');
        });
        Schema::table('places', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
        });
        */
        // 外部キー制約を無効にする
        Schema::disableForeignKeyConstraints();

        Schema::table('places', function (Blueprint $table) {
            // user_idカラムを一旦削除して再追加することで順序を変更
            $table->dropColumn('user_id');
        });

        Schema::table('places', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
        });

        // 外部キー制約を有効にする
        Schema::enableForeignKeyConstraints();
    
    }

    public function down()
    {
        Schema::table('places', function (Blueprint $table) {
            // 外部キー制約を無効にする
            Schema::disableForeignKeyConstraints();

            // user_idカラムを元に戻す
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // 外部キー制約を有効にする
            Schema::enableForeignKeyConstraints();
        });

        Schema::table('places', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
        });
    }
};
