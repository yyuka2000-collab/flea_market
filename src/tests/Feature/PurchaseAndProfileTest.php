<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PurchaseAndProfileTest extends TestCase
{
    use WithoutMiddleware;

    // =========================================================
    // 商品購入機能
    // =========================================================

    /**
     * @test
     * 「購入する」ボタンを押下すると購入が完了する
     */
    public function user_can_purchase_an_item(): void
    {
        $user    = \App\Models\User::find(1);
        $product = \App\Models\Product::where('user_id', '!=', 1)->first();

        $response = $this->actingAs($user)->post("/purchase/{$product->id}", [
            'payment_method'   => 'convenience',
            'shipping_address' => '東京都渋谷区1-1-1',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('orders', [
            'user_id'    => $user->id,
            'product_id' => $product->id,
        ]);
    }

    /**
     * @test
     * 購入した商品は商品一覧画面にて「sold」と表示される
     */
    public function purchased_item_shows_sold_label_in_item_list(): void
    {
        $user     = \App\Models\User::find(1);
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    /**
     * @test
     * 「プロフィール/購入した商品一覧」に追加されている
     */
    public function purchased_item_appears_in_profile_purchase_list(): void
    {
        $user    = \App\Models\User::find(1);
        $product = \App\Models\Product::where('user_id', '!=', 1)->first();

        $this->actingAs($user)->post("/purchase/{$product->id}", [
            'payment_method'   => 'card',
            'shipping_address' => '東京都渋谷区1-1-1',
        ]);

        $response = $this->actingAs($user)->get('/mypage?tab=buy');

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    // =========================================================
    // 支払い方法選択機能
    // =========================================================

    /**
     * @test
     * 小計画面で変更が反映される
     */
    public function selected_payment_method_is_reflected_on_order_summary(): void
    {
        $user    = \App\Models\User::find(1);
        $product = \App\Models\Product::where('user_id', '!=', 1)->first();

        $response = $this->actingAs($user)
                         ->get("/purchase/{$product->id}?payment_method=convenience");

        $response->assertStatus(200);
        $response->assertSee('コンビニ支払い');
    }

    // =========================================================
    // 配送先変更機能
    // =========================================================

    /**
     * @test
     * 送付先住所変更画面にて登録した住所が商品購入画面に反映されている
     */
    public function registered_shipping_address_is_reflected_on_purchase_page(): void
    {
        $user    = \App\Models\User::find(1);
        $product = \App\Models\Product::where('user_id', '!=', 1)->first();
        $profile = $user->profile;

        // 購入画面にプロフィールの住所が表示されることを確認
        $response = $this->actingAs($user)->get("/purchase/{$product->id}");

        $response->assertStatus(200);
        // プロフィールに住所が登録されている場合はその住所が表示される
        if ($profile && $profile->address) {
            $response->assertSee($profile->address);
        } else {
            $response->assertSee('住所が登録されていません');
        }
    }

    /**
     * @test
     * 購入した商品に送付先住所が紐づいて登録される
     */
    public function purchased_item_is_linked_with_shipping_address(): void
    {
        $user    = \App\Models\User::find(1);
        $product = \App\Models\Product::where('user_id', '!=', 1)->first();
        $profile = $user->profile;

        $this->actingAs($user)->post("/purchase/{$product->id}", [
            'payment_method' => 'convenience',
        ]);

        // プロフィールの住所が orders に紐づいて保存されることを確認
        $expectedAddress = $profile->address ?? '';
        $this->assertDatabaseHas('orders', [
            'user_id'          => $user->id,
            'product_id'       => $product->id,
            'shipping_address' => $expectedAddress,
        ]);
    }

    // =========================================================
    // ユーザー情報取得
    // =========================================================

    /**
     * @test
     * 必要な情報が取得できる
     */
    public function profile_page_shows_all_required_user_information(): void
    {
        $user = \App\Models\User::find(1);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    // =========================================================
    // ユーザー情報変更
    // =========================================================

    /**
     * @test
     * 変更項目が初期値として過去設定されていること
     */
    public function profile_edit_page_shows_current_values_as_defaults(): void
    {
        $user = \App\Models\User::find(1);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    // =========================================================
    // 出品商品情報登録
    // =========================================================

    /**
     * @test
     * 商品出品画面にて必要な情報が保存できること
     */
    public function user_can_register_item_with_all_required_fields(): void
    {
        Storage::fake('public');
        $user  = \App\Models\User::find(1);
        $image = UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->post('/sell', [
            'name'         => '新商品テスト',
            'brand_name'   => 'テストブランド',
            'description'  => 'テスト商品の説明文',
            'price'        => 5000,
            'condition'    => '良好',
            'category_ids' => [1, 2],
            'image'        => $image,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('products', [
            'name'        => '新商品テスト',
            'brand_name'  => 'テストブランド',
            'description' => 'テスト商品の説明文',
            'price'       => 5000,
            'condition'   => '良好',
            'user_id'     => $user->id,
        ]);
    }
}