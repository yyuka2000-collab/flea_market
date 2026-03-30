<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'user_id'     => 2,
                'name'        => '腕時計',
                'brand_name'  => 'Rolax',
                'image_url'   => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'filename'    => 'Armani_Mens_Clock.jpg',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price'       => 15000,
                'condition'   => '良好',
                'sold_flg'    => false,
            ],
            [
                'user_id'     => 2,
                'name'        => 'HDD',
                'brand_name'  => '西芝',
                'image_url'   => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'filename'    => 'HDD_Hard_Disk.jpg',
                'description' => '高速で信頼性の高いハードディスク',
                'price'       => 5000,
                'condition'   => '目立った傷や汚れなし',
                'sold_flg'    => false,
            ],
            [
                'user_id'     => 2,
                'name'        => '玉ねぎ3束',
                'brand_name'  => null,
                'image_url'   => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'filename'    => 'iLoveIMG_d.jpg',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price'       => 300,
                'condition'   => 'やや傷や汚れあり',
                'sold_flg'    => false,
            ],
            [
                'user_id'     => 2,
                'name'        => '革靴',
                'brand_name'  => null,
                'image_url'   => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'filename'    => 'Leather_Shoes_Product_Photo.jpg',
                'description' => 'クラシックなデザインの革靴',
                'price'       => 4000,
                'condition'   => '状態が悪い',
                'sold_flg'    => false,
            ],
            [
                'user_id'     => 2,
                'name'        => 'ノートPC',
                'brand_name'  => null,
                'image_url'   => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'filename'    => 'Living_Room_Laptop.jpg',
                'description' => '高性能なノートパソコン',
                'price'       => 45000,
                'condition'   => '良好',
                'sold_flg'    => false,
            ],
            [
                'user_id'     => 2,
                'name'        => 'マイク',
                'brand_name'  => null,
                'image_url'   => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'filename'    => 'Music_Mic_4632231.jpg',
                'description' => '高音質のレコーディング用マイク',
                'price'       => 8000,
                'condition'   => '目立った傷や汚れなし',
                'sold_flg'    => false,
            ],
            [
                'user_id'     => 2,
                'name'        => 'ショルダーバッグ',
                'brand_name'  => null,
                'image_url'   => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'filename'    => 'Purse_fashion_pocket.jpg',
                'description' => 'おしゃれなショルダーバッグ',
                'price'       => 3500,
                'condition'   => 'やや傷や汚れあり',
                'sold_flg'    => false,
            ],
            [
                'user_id'     => 2,
                'name'        => 'タンブラー',
                'brand_name'  => null,
                'image_url'   => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'filename'    => 'Tumbler_souvenir.jpg',
                'description' => '使いやすいタンブラー',
                'price'       => 500,
                'condition'   => '状態が悪い',
                'sold_flg'    => false,
            ],
            [
                'user_id'     => 2,
                'name'        => 'コーヒーミル',
                'brand_name'  => 'Starbacks',
                'image_url'   => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'filename'    => 'Waitress_with_Coffee_Grinder.jpg',
                'description' => '手動のコーヒーミル',
                'price'       => 4000,
                'condition'   => '良好',
                'sold_flg'    => false,
            ],
            [
                'user_id'     => 2,
                'name'        => 'メイクセット',
                'brand_name'  => null,
                'image_url'   => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'filename'    => 'makeup_set.jpg',
                'description' => '便利なメイクアップセット',
                'price'       => 2500,
                'condition'   => '目立った傷や汚れなし',
                'sold_flg'    => false,
            ],
        ];

        foreach ($products as $data) {
            // 画像をダウンロードしてstorageに保存
            $path = 'products/' . $data['filename'];

            if (!Storage::disk('public')->exists($path)) {
                $response = Http::get($data['image_url']);
                if ($response->successful()) {
                    Storage::disk('public')->put($path, $response->body());
                }
            }

            // DBに保存（image_path にはローカルパスを入れる）
            Product::create([
                'user_id'     => $data['user_id'],
                'name'        => $data['name'],
                'brand_name'  => $data['brand_name'],
                'image_path'  => $path,
                'description' => $data['description'],
                'price'       => $data['price'],
                'condition'   => $data['condition'],
                'sold_flg'    => $data['sold_flg'],
            ]);
        }
    }
}