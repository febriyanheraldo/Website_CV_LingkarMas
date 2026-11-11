<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_berhasil(): void
    {
        $this->postJson('/api/register', [
            'nama' => 'Budi', 'email' => 'budi@test.com', 'hp' => '0812', 'password' => 'rahasia',
        ])->assertStatus(201)->assertJsonStructure(['message']);

        $this->assertDatabaseHas('users', ['email' => 'budi@test.com', 'role' => 'klien']);
    }

    public function test_register_email_duplikat(): void
    {
        User::factory()->create(['email' => 'budi@test.com']);

        $this->postJson('/api/register', [
            'nama' => 'Budi', 'email' => 'budi@test.com', 'hp' => '0812', 'password' => 'rahasia',
        ])->assertStatus(422)->assertJson(['message' => 'Email sudah terdaftar']);
    }

    public function test_login_salah_membalas_401(): void
    {
        User::factory()->create(['email' => 'budi@test.com']);

        $this->postJson('/api/login', ['email' => 'budi@test.com', 'password' => 'salah'])
            ->assertStatus(401)->assertJson(['message' => 'Email atau password salah']);
    }

    public function test_login_berhasil_mengembalikan_token_dan_user(): void
    {
        User::factory()->create(['email' => 'budi@test.com']);   // password: "password"

        $this->postJson('/api/login', ['email' => 'budi@test.com', 'password' => 'password'])
            ->assertOk()
            ->assertJsonStructure(['token', 'user' => ['id', 'nama', 'email', 'hp', 'role']]);
    }

    public function test_admin_tidak_boleh_login_lewat_api(): void
    {
        User::factory()->admin()->create(['email' => 'admin@test.com']);

        $this->postJson('/api/login', ['email' => 'admin@test.com', 'password' => 'password'])
            ->assertStatus(403);
    }

    public function test_tanpa_token_membalas_401_json(): void
    {
        $this->getJson('/api/me')->assertStatus(401)->assertJsonStructure(['message', 'errors']);
    }
}
