<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ReportStatusTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function government_user_can_update_report_status()
    {
        // Buat user pemerintah
        $government = User::factory()->create([
            'role' => 'pemerintah'
        ]);

        // Buat user masyarakat
        $masyarakat = User::factory()->create([
            'role' => 'masyarakat'
        ]);

        // Buat laporan (TANPA factory)
        $report = Report::create([
            'user_id' => $masyarakat->id,
            'title' => 'Banjir daerah Ketintang',
            'description' => 'Air meluap hingga jalan utama',
            'latitude' => -7.303,
            'longitude' => 112.732,
            'address' => 'Ketintang, Surabaya',
            'status' => 'pending',
        ]);

        // Pemerintah update status
        $response = $this->actingAs($government)
            ->post(route('admin.reports.updateStatus', $report), [
                'status' => 'selesai'
            ]);

        // Pastikan update berhasil
        $response->assertSessionHas('success');

        // Pastikan status berubah di database
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'selesai'
        ]);
    }
}
