<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_email_required()//メールアドレス未入力
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertStatus(302);//HTTPステータスコードが302（リダイレクト）であることを確認
        $response->assertSessionHasErrors(['email']);//セッションに 'email' のバリデーションエラーがあることを確認
    }

    public function test_login_password_required()//パスワード未入力
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_login_invalid_credentials()//入力情報間違い
    {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_login_successful()//正常ログイン
    {
        $user = User::factory()->create([
            'email'=>'test@example.com',
            'password'=> Hash::make('password123')
        ]);

        //ユーザーがログインできたかどうかを確認
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        //現在ログインしているユーザーが$userであることを確認
        $this->assertAuthenticatedAs($user);
    }
}