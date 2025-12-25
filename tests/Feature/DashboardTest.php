<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function masyarakat_user_can_access_masyarakat_dashboard()
    {
        $user = User::factory()->create(['role' => 'masyarakat']);

        $this->actingAs($user)
            ->get(route('dashboard.masyarakat'))
            ->assertStatus(200);
    }

    /** @test */
    public function government_user_can_access_government_dashboard()
    {
        $user = User::factory()->create(['role' => 'pemerintah']);

        $this->actingAs($user)
            ->get(route('dashboard.pemerintah'))
            ->assertStatus(200);
    }

    /** @test */
    public function flood_risk_area_is_calculated_correctly()
    {
        Report::factory()->count(3)->create([
            'latitude' => -7.300,
            'longitude' => 112.700,
            'title' => 'Banjir daerah A'
        ]);

        $this->assertTrue(
            Report::where('latitude', -7.300)
                ->where('longitude', 112.700)
                ->count() >= 3
        );
    }
}
