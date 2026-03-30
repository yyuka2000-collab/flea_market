<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // product_id => category_id の対応
        // カテゴリID参考:
        //  1:ファッション, 2:家電, 3:インテリア, 4:レディース
        //  5:メンズ, 6:コスメ, 7:本
        //  8:ゲーム, 9:スポーツ, 10:キッチン
        // 11:ハンドメイド, 12:アクセサリー, 13:おもちゃ
        // 14:ベビー・キッズ

        $productCategories = [
            // 腕時計 (product_id=1) → メンズ, ファッション小物
            ['product_id' => 1, 'category_id' => 1],
            ['product_id' => 1, 'category_id' => 5],

            // HDD (product_id=2) → 家電
            ['product_id' => 2, 'category_id' => 2],

            // 玉ねぎ3束 (product_id=3) → キッチン
            ['product_id' => 3, 'category_id' => 10],

            // 革靴 (product_id=4) → メンズ
            ['product_id' => 4, 'category_id' => 5],

            // ノートPC (product_id=5) → 家電
            ['product_id' => 5, 'category_id' => 2],

            // マイク (product_id=6) → 家電, ゲーム
            ['product_id' => 6, 'category_id' => 2],
            ['product_id' => 6, 'category_id' => 8],

            // ショルダーバッグ (product_id=7) → レディース, ファッション
            ['product_id' => 7, 'category_id' => 1],
            ['product_id' => 7, 'category_id' => 4],

            // タンブラー (product_id=8) → インテリア
            ['product_id' => 8, 'category_id' => 3],

            // コーヒーミル (product_id=9) → インテリア
            ['product_id' => 9, 'category_id' => 3],

            // メイクセット (product_id=10) → コスメ
            ['product_id' => 10, 'category_id' => 6],
        ];

        DB::table('product_category')->insert($productCategories);
    }
}
