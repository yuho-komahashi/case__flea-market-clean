<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_register_name_required()//名前未入力
    {
        $response = $this->post('/register',[
            'name'=>'',
            'email'=>'test@example.com',
            'password'=>'password123',
            'password_confirmation'=>'password123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }

    public function test_register_email_required()//メールアドレス未入力
    {
        $response = $this->post('/register',[
            'name'=>'テスト太郎',
            'email'=>'',
            'password'=>'password123',
            'password_confirmation'=>'password123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_register_password_required()//パスワード未入力
    {
        $response = $this->post('/register',[
            'name'=>'テスト太郎',
            'email'=>'test@example.com',
            'password'=>'',
            'password_confirmation'=>'',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_register_password_too_short()//パスワード7文字以下
    {
        $response = $this->post('/register',[
            'name'=>'テスト太郎',
            'email'=>'test@example.com',
            'password'=>'short',
            'password_confirmation'=>'short',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);
    }
    
    public function test_register_password_confirmation_mismatch()//パスワード確認と不一致
    {
        $response = $this->post('/register',[
            'name'=>'テスト太郎',
            'email'=>'test@example.com',
            'password'=>'password123',
            'password_confirmation'=>'different123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password_confirmation']);
    }

    public function test_register_successful()//正常登録
    {
        $response = $this->post('/register',[
            'name'=>'テスト太郎',
            'email'=>'test@example.com',
            'password'=>'password123',
            'password_confirmation'=>'password123',
        ]);

        $response->assertRedirect('/email/verify');
        //usersテーブルに、email が test@example.com のレコードが存在することを確認
        $this->assertDatabaseHas('users',[
            'email'=>'test@example.com',
        ]);
    }

}
