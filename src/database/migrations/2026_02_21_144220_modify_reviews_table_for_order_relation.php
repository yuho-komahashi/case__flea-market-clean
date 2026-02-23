<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyReviewsTableForOrderRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. 旧外部キー削除
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        // 2. 新カラム追加
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('reviewee_id');
        });

        // 3. データ移行
        DB::table('reviews')->update([
            'order_id' => DB::raw('item_id'),
        ]);

        // 4. 旧カラム削除＆新外部キー追加
        Schema::table('reviews', function (Blueprint $table){
            // 旧カラム削除
            $table->dropColumn('item_id');

            // 新外部キー追加
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
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });

        // 2. 旧カラム復元
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->nullable()->after('reviewee_id');
        });

        // 3. データ戻し
        DB::table('reviews')->update([
            'item_id' => DB::raw('order_id'),
        ]);

        // 4. 新カラム削除 & 旧外部キー復元
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('order_id');
            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->cascadeOnDelete();
        });
    }
}

/*修正後のreviews_table
'id',
'reviewer_id',
'reviewee_id',
'order_id',
'score',
'created_at',
'updated_at',
*/
