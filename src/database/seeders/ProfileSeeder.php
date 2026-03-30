<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profiles = [
            [
                'user_id'     => 1,
                'image_path'  => null,
                'postal_code' => '100-0001',
                'address'     => '東京都千代田区千代田1-1',
                'building'    => 'テストビル101',
            ],
            [
                'user_id'     => 2,
                'image_path'  => null,
                'postal_code' => '530-0001',
                'address'     => '大阪府大阪市北区梅田1-1',
                'building'    => 'サンプルマンション202',
            ],
            [
                'user_id'     => 3,
                'image_path'  => null,
                'postal_code' => '460-0001',
                'address'     => '愛知県名古屋市中区三の丸1-1',
                'building'    => null,
            ],
        ];

        foreach ($profiles as $profile) {
            Profile::create($profile);
        }
    }
}
