<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\PengajuanIzin;
use App\Models\Presensi;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class WaliKelasBusinessLogicTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_dashboard_renders_today_attendance_summary_for_assigned_class(): void
    {
        $this->travelTo('2026-09-15 08:00:00');

        [$waliKelas, $kelas] = $this->waliKelasDenganKelas();
        $hadir = $this->tambahkanSiswaKeKelas($kelas);
        $izin = $this->tambahkanSiswaKeKelas($kelas);
        $sakit = $this->tambahkanSiswaKeKelas($kelas);
        $alpha = $this->tambahkanSiswaKeKelas($kelas);

        Presensi::factory()->create(['user_id' => $hadir->id, 'tanggal' => today(), 'status' => 'Hadir']);
        Presensi::factory()->create(['user_id' => $izin->id, 'tanggal' => today(), 'status' => 'Izin']);
        Presensi::factory()->create(['user_id' => $sakit->id, 'tanggal' => today(), 'status' => 'Sakit']);

        $this->withoutVite();

        $this->actingAs($waliKelas)
            ->get(route('wali-kelas.dashboard'))
            ->assertViewIs('wali-kelas.dashboard')
            ->assertViewHas('ringkasan', function (array $ringkasan): bool {
                return $ringkasan === ['Hadir' => 1, 'Izin' => 1, 'Sakit' => 1, 'Alpha' => 1];
            })
            ->assertSeeText($alpha->name);

        $this->travelBack();
    }

    public function test_approving_pending_sick_request_updates_daily_attendance(): void
    {
        [$waliKelas, $kelas] = $this->waliKelasDenganKelas();
        $siswa = $this->tambahkanSiswaKeKelas($kelas);
        $pengajuan = PengajuanIzin::factory()->create([
            'siswa_id' => $siswa->id,
            'tanggal' => '2026-09-15',
            'jenis' => 'sakit',
        ]);
        $presensi = Presensi::factory()->create([
            'user_id' => $siswa->id,
            'tanggal' => '2026-09-15',
            'status' => 'Hadir',
        ]);

        $this->actingAs($waliKelas)
            ->patch(route('wali-kelas.pengajuan-izin.approve', $pengajuan), [
                'catatan_verifikasi' => 'Surat dokter telah diperiksa.',
            ])
            ->assertRedirectToRoute('wali-kelas.pengajuan-izin.index');

        $this->assertDatabaseHas('pengajuan_izins', [
            'id' => $pengajuan->id,
            'status' => 'disetujui',
            'diverifikasi_oleh' => $waliKelas->id,
        ]);
        $this->assertDatabaseHas('presensis', [
            'id' => $presensi->id,
            'status' => 'Sakit',
        ]);
    }

    public function test_wali_kelas_cannot_approve_request_from_student_outside_assigned_class(): void
    {
        [$waliKelas] = $this->waliKelasDenganKelas();
        $pengajuan = PengajuanIzin::factory()->create();

        $this->actingAs($waliKelas)
            ->patch(route('wali-kelas.pengajuan-izin.approve', $pengajuan))
            ->assertNotFound();

        $this->assertDatabaseHas('pengajuan_izins', [
            'id' => $pengajuan->id,
            'status' => 'menunggu',
        ]);
        $this->assertDatabaseMissing('presensis', [
            'user_id' => $pengajuan->siswa_id,
            'tanggal' => $pengajuan->tanggal->format('Y-m-d'),
            'mata_kuliah_id' => null,
        ]);
    }

    public function test_rejecting_pending_request_records_the_verification_without_creating_attendance(): void
    {
        [$waliKelas, $kelas] = $this->waliKelasDenganKelas();
        $siswa = $this->tambahkanSiswaKeKelas($kelas);
        $pengajuan = PengajuanIzin::factory()->create(['siswa_id' => $siswa->id]);

        $this->actingAs($waliKelas)
            ->patch(route('wali-kelas.pengajuan-izin.reject', $pengajuan), [
                'catatan_verifikasi' => 'Lampiran tidak dapat diverifikasi.',
            ])
            ->assertRedirectToRoute('wali-kelas.pengajuan-izin.index');

        $this->assertDatabaseHas('pengajuan_izins', [
            'id' => $pengajuan->id,
            'status' => 'ditolak',
            'diverifikasi_oleh' => $waliKelas->id,
            'catatan_verifikasi' => 'Lampiran tidak dapat diverifikasi.',
        ]);
        $this->assertDatabaseMissing('presensis', [
            'user_id' => $siswa->id,
            'tanggal' => $pengajuan->tanggal->format('Y-m-d'),
            'mata_kuliah_id' => null,
        ]);
    }

    public function test_correcting_attendance_creates_an_audit_log(): void
    {
        [$waliKelas, $kelas] = $this->waliKelasDenganKelas();
        $siswa = $this->tambahkanSiswaKeKelas($kelas);
        $presensi = Presensi::factory()->create([
            'user_id' => $siswa->id,
            'status' => 'Alpha',
        ]);

        $this->actingAs($waliKelas)
            ->patch(route('wali-kelas.presensi.koreksi', $presensi), [
                'status' => 'Hadir',
                'alasan_koreksi' => 'Siswa hadir tetapi presensi belum tercatat.',
            ])
            ->assertRedirectToRoute('wali-kelas.dashboard');

        $this->assertDatabaseHas('presensis', [
            'id' => $presensi->id,
            'status' => 'Hadir',
        ]);
        $this->assertDatabaseHas('presensi_logs', [
            'presensi_id' => $presensi->id,
            'diubah_oleh' => $waliKelas->id,
            'status_sebelumnya' => 'Alpha',
            'status_terbaru' => 'Hadir',
            'alasan_koreksi' => 'Siswa hadir tetapi presensi belum tercatat.',
        ]);
    }

    public function test_recap_uses_the_requested_date_range(): void
    {
        [$waliKelas, $kelas] = $this->waliKelasDenganKelas();
        $siswa = $this->tambahkanSiswaKeKelas($kelas);

        Presensi::factory()->create(['user_id' => $siswa->id, 'tanggal' => '2026-09-15', 'status' => 'Hadir']);
        Presensi::factory()->create(['user_id' => $siswa->id, 'tanggal' => '2026-09-16', 'status' => 'Izin']);
        Presensi::factory()->create(['user_id' => $siswa->id, 'tanggal' => '2026-09-17', 'status' => 'Sakit']);

        $this->withoutVite();

        $this->actingAs($waliKelas)
            ->get(route('wali-kelas.rekap', [
                'tanggal_mulai' => '2026-09-15',
                'tanggal_selesai' => '2026-09-17',
            ]))
            ->assertViewIs('wali-kelas.rekap')
            ->assertViewHas('rekapSiswa', function (Collection $rekapSiswa): bool {
                $rekap = $rekapSiswa->first();

                return $rekap->hadir === 1
                    && $rekap->izin === 1
                    && $rekap->sakit === 1
                    && $rekap->alpha === 0;
            });
    }

    /**
     * @return array{User, Kelas}
     */
    private function waliKelasDenganKelas(): array
    {
        $waliKelas = User::factory()->waliKelas()->create();
        $kelas = Kelas::factory()->create();

        $kelas->waliKelas()->attach($waliKelas, [
            'aktif' => true,
            'mulai_pada' => '2026-07-01',
        ]);

        return [$waliKelas, $kelas];
    }

    private function tambahkanSiswaKeKelas(Kelas $kelas): User
    {
        $siswa = User::factory()->siswa()->create();

        $kelas->siswa()->attach($siswa, [
            'aktif' => true,
            'mulai_pada' => '2026-07-01',
        ]);

        return $siswa;
    }
}
