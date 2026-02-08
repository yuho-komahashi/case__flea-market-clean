<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\URL;

class VerificationTest extends TestCase
{
    public function test_email_verification_page_displays_verification_button()//「認証はこちらから」ボタンの遷移確認
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('verification.notice'));
        $response->assertStatus(200);
        $response->assertSee('認証はこちらから');
    }

    public function test_user_can_verify_email_and_redirects_to_mylist()//メール認証完了 → プロフィール設定画面へ
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->get($verificationUrl);

        $response->assertRedirect(route('mypage.profile.create'));
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}