<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyMessagesTableForOrderRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. item_id の外部キー削除
        Schema::table('messages', function (Blueprint $table){
            $table->dropForeign(['item_id']);
        });

        // 2. 新カラム追加（order_id, message）
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('user_id');
            $table->text('message')->nullable()->after('order_id');
        });

        // 3. データ移行（item_id → order_id、body → message）
        DB::table('messages')->update([
            'order_id' => DB::raw('item_id'),
            'message' => DB::raw('body'),
        ]);

        // 4. 旧カラム削除 ＆ 新外部キー追加
        Schema::table('messages', function (Blueprint $table){
            // 旧カラム削除
            $table->dropColumn('item_id');
            $table->dropColumn('body');

            // 新しい外部キーを追加
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 1. 新外部キー削除
        Schema::table('messages', function (Blueprint $table){
            $table->dropForeign(['order_id']);
        });

        // 2. 旧カラム復元
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->nullable()->after('user_id');
            $table->text('body')->nullable()->after('item_id');
        });

        // 3. データ戻し
        DB::table('messages')->update([
            'item_id' => DB::raw('order_id'),
            'body' => DB::raw('message'),
        ]);

        // 4. 新カラム削除 ＆ 旧外部キー復元
        Schema::table('messages', function (Blueprint $table) {
            // 新カラム削除
            $table->dropColumn('order_id');
            $table->dropColumn('message');

            // 旧外部キー復元
            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->cascadeOnDelete();
        });
    }
}

/*修正後のmessages_table
'id',
'user_id',
'order_id',
'message',
'image',
'is_read',
'created_at',
'updated_at',
*/
