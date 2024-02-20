<?php

namespace Tests\Feature;

use App\Models\ResetPassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_forget_password_with_valid_data()
    {
        $user = User::factory()->create();
        $resetPassword = ResetPassword::factory()->create(['user_id' => $user->id, 'token' => '12345']);

        $response = $this->postJson('/api/forget-password', [
            'phone_number' => $user->phone_number,
            'token' => '12345',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Password reset successfully']);

        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
        $this->assertNull($user->fresh()->resetPassword->token);
    }

}
