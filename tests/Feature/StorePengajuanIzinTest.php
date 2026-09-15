<?php

namespace Tests\Feature;

use App\Models\PengajuanIzin;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorePengajuanIzinTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_siswa_can_submit_leave_request_with_evidence_file(): void
    {
        $this->travelTo('2026-09-15 08:00:00');
        Storage::fake('public');
        $siswa = User::factory()->siswa()->create();
        $lampiran = UploadedFile::fake()->create('surat-dokter.pdf', 100, 'application/pdf');

        $this->actingAs($siswa)
            ->post(route('siswa.pengajuan-izin.store'), [
                'tanggal' => '2026-09-15',
                'jenis' => 'sakit',
                'alasan' => 'Demam dan perlu beristirahat.',
                'lampiran' => $lampiran,
            ])
            ->assertRedirectToRoute('siswa.dashboard');

        $this->assertDatabaseHas('pengajuan_izins', [
            'siswa_id' => $siswa->id,
            'jenis' => 'sakit',
            'status' => 'menunggu',
        ]);
        $this->assertSame('2026-09-15', PengajuanIzin::sole()->tanggal->toDateString());
        Storage::disk('public')->assertExists('pengajuan-izin/'.$siswa->id.'/'.$lampiran->hashName());

        $this->travelBack();
    }
}
