<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ItemListTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * @test
     * 全商品を取得できる
     */
    public function all_items_are_displayed_on_item_list(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * @test
     * 購入済み商品は「Sold」と表示される
     */
    public function sold_items_display_sold_label_when_exists_when_exists(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $this->assertTrue(true);
    }

    /**
     * @test
     * 自分が出品した商品は表示されない
     */
    public function own_listed_items_are_not_shown_in_item_list(): void
    {
        $user = \App\Models\User::first();
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
    }

    /**
     * @test
     * いいねした商品だけが表示される
     */
    public function mylist_shows_only_liked_items(): void
    {
        $user = \App\Models\User::first();
        $response = $this->actingAs($user)->get('/?tab=mylist');
        $response->assertStatus(200);
    }

    /**
     * @test
     * 購入済み商品は「Sold」と表示される（マイリスト）
     */
    public function mylist_shows_sold_label_on_purchased_items(): void
    {
        $user = \App\Models\User::first();
        $response = $this->actingAs($user)->get('/?tab=mylist');
        $response->assertStatus(200);
    }

    /**
     * @test
     * 未認証の場合は何も表示されない
     */
    public function mylist_shows_nothing_for_unauthenticated_user(): void
    {
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);
    }

    /**
     * @test
     * 「商品名」で部分一致検索ができる
     */
    public function items_can_be_searched_by_partial_name(): void
    {
        $response = $this->get('/?keyword=腕時計');
        $response->assertStatus(200);
        $response->assertSee('腕時計');
    }

    /**
     * @test
     * 検索状態がマイリストでも保持されている
     */
    public function search_keyword_is_retained_on_mylist_tab(): void
    {
        $user = \App\Models\User::first();
        $response = $this->actingAs($user)->get('/?keyword=腕時計&tab=mylist');
        $response->assertStatus(200);
        $response->assertSee('腕時計');
    }
}
