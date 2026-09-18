<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AuthenticationAndRoleAccessTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_siswa_is_redirected_to_student_dashboard_after_login(): void
    {
        $siswa = User::factory()->siswa()->create([
            'nomor_induk' => '0012345678',
            'password' => 'password',
        ]);

        $this->post(route('login.store'), [
            'nomor_induk' => $siswa->nomor_induk,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->get(route('dashboard'))->assertRedirect(route('siswa.dashboard'));
    }

    public function test_wali_kelas_is_redirected_to_homeroom_dashboard_after_login(): void
    {
        $waliKelas = User::factory()->waliKelas()->create([
            'nomor_induk' => '1987654321',
            'password' => 'password',
        ]);

        $this->post(route('login.store'), [
            'nomor_induk' => $waliKelas->nomor_induk,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->get(route('dashboard'))->assertRedirect(route('wali-kelas.dashboard'));
    }

    public function test_siswa_cannot_access_wali_kelas_dashboard(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->get(route('wali-kelas.dashboard'))
            ->assertForbidden();
    }

    public function test_siswa_cannot_access_wali_kelas_management_routes(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->get(route('wali-kelas.mata-kuliah.index'))
            ->assertForbidden();
    }

    public function test_wali_kelas_cannot_access_siswa_dashboard(): void
    {
        $waliKelas = User::factory()->waliKelas()->create();

        $this->actingAs($waliKelas)
            ->get(route('siswa.dashboard'))
            ->assertForbidden();
    }

    public function test_siswa_can_access_own_dashboard(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->withoutVite();

        $this->actingAs($siswa)
            ->get(route('siswa.dashboard'))
            ->assertSuccessful();
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        User::factory()->siswa()->create([
            'nomor_induk' => '0012345678',
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'nomor_induk' => '0012345678',
                'password' => 'incorrect-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('nomor_induk');
    }
}
