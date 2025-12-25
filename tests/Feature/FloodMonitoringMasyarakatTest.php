<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FloodMonitoringMasyarakatTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function masyarakat_can_only_see_completed_flood_reports_on_map()
    {
        // User masyarakat
        $masyarakat = User::factory()->create([
            'role' => 'masyarakat'
        ]);

        // Laporan selesai
        Report::create([
            'user_id' => $masyarakat->id,
            'title' => 'Banjir daerah Ketintang',
            'description' => 'Banjir sudah surut',
            'latitude' => -7.303,
            'longitude' => 112.732,
            'status' => 'selesai'
        ]);

        // Laporan pending
        Report::create([
            'user_id' => $masyarakat->id,
            'title' => 'Banjir daerah Wonokromo',
            'description' => 'Masih terjadi genangan',
            'latitude' => -7.291,
            'longitude' => 112.736,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($masyarakat)
            ->get(route('dashboard.masyarakat'));

        // Hanya laporan selesai yang terlihat
        $response->assertSee('Banjir daerah Ketintang');
        $response->assertDontSee('Banjir daerah Wonokromo');
    }
}
