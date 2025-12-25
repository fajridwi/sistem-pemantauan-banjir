<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FloodMonitoringPemerintahTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function government_can_see_all_flood_report_locations_on_map()
    {
        // User pemerintah
        $pemerintah = User::factory()->create([
            'role' => 'pemerintah'
        ]);

        // Laporan pending
        Report::create([
            'user_id' => $pemerintah->id,
            'title' => 'Banjir daerah Rungkut',
            'description' => 'Menunggu penanganan',
            'latitude' => -7.321,
            'longitude' => 112.778,
            'status' => 'pending'
        ]);

        // Laporan selesai
        Report::create([
            'user_id' => $pemerintah->id,
            'title' => 'Banjir daerah Dukuh Kupang',
            'description' => 'Sudah ditangani',
            'latitude' => -7.289,
            'longitude' => 112.719,
            'status' => 'selesai'
        ]);

        $response = $this->actingAs($pemerintah)
            ->get(route('dashboard.pemerintah'));

        // Semua laporan terlihat
        $response->assertSee('Banjir daerah Rungkut');
        $response->assertSee('Banjir daerah Dukuh Kupang');
    }
}
