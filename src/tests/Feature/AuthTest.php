<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use WithoutMiddleware;

    // =========================================================
    // 会員登録機能
    // =========================================================

    /**
     * @test
     * 名前が入力されていない場合、バリデーションメッセージが表示される
     */
    public function registration_fails_without_name(): void
    {
        $response = $this->post('/register', [
            'name'                  => '',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['name']);
        $errors = session('errors')->get('name');
        $this->assertContains('お名前を入力してください', $errors);
    }

    /**
     * @test
     * メールアドレスが入力されていない場合、バリデーションメッセージが表示される
     */
    public function registration_fails_without_email(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => '',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $errors = session('errors')->get('email');
        $this->assertContains('メールアドレスを入力してください', $errors);
    }

    /**
     * @test
     * パスワードが入力されていない場合、バリデーションメッセージが表示される
     */
    public function registration_fails_without_password(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => 'test@example.com',
            'password'              => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
        $errors = session('errors')->get('password');
        $this->assertContains('パスワードを入力してください', $errors);
    }

    /**
     * @test
     * パスワードが7文字以下の場合、バリデーションメッセージが表示される
     */
    public function registration_fails_with_password_less_than_8_characters(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => 'test@example.com',
            'password'              => 'pass123',
            'password_confirmation' => 'pass123',
        ]);

        $response->assertSessionHasErrors(['password']);
        $errors = session('errors')->get('password');
        $this->assertContains('パスワードは8文字以上で入力してください', $errors);
    }

    /**
     * @test
     * パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される
     */
    public function registration_fails_when_password_confirmation_does_not_match(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'different123',
        ]);

        $response->assertSessionHasErrors(['password']);
        $errors = session('errors')->get('password');
        $this->assertContains('パスワードと一致しません', $errors);
    }

    /**
     * @test
     * 全ての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される
     */
    public function registration_succeeds_with_valid_data_and_redirects_to_profile(): void
    {
        // 既存ユーザーと重複しないメールアドレスを使用
        $email = 'register_test_' . time() . '@example.com';

        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => $email,
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', ['email' => $email]);
        $response->assertRedirect('/email/verify');
    }

    // =========================================================
    // ログイン機能
    // =========================================================

    /**
     * @test
     * メールアドレスが入力されていない場合、バリデーションメッセージが表示される
     */
    public function login_fails_without_email(): void
    {
        $response = $this->post('/login', [
            'email'    => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $errors = session('errors')->get('email');
        $this->assertContains('メールアドレスを入力してください', $errors);
    }

    /**
     * @test
     * パスワードが入力されていない場合、バリデーションメッセージが表示される
     */
    public function login_fails_without_password(): void
    {
        $response = $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
        $errors = session('errors')->get('password');
        $this->assertContains('パスワードを入力してください', $errors);
    }

    /**
     * @test
     * 入力情報が間違っている場合、バリデーションメッセージが表示される
     */
    public function login_fails_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'email'    => 'notregistered@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $errors = session('errors')->get('email');
        $this->assertContains('ログイン情報が登録されていません', $errors);
    }

    /**
     * @test
     * 正しい情報が入力された場合、ログイン処理が実行される
     */
    public function login_succeeds_with_valid_credentials(): void
    {
        // シーダーで作成済みの既存ユーザーを使用
        $user = \App\Models\User::find(1);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }

    // =========================================================
    // ログアウト機能
    // =========================================================

    /**
     * @test
     * ログアウトができる
     */
    public function user_can_logout(): void
    {
        $user = \App\Models\User::find(1);

        $this->actingAs($user)
             ->post('/logout')
             ->assertRedirect('/login');

        $this->assertGuest();
    }
}