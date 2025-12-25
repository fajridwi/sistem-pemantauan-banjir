<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authenticated_user_can_create_a_report()
    {
        $user = User::factory()->create([
            'role' => 'masyarakat'
        ]);

        $this->actingAs($user)->post(route('reports.store'), [
            'title' => 'Banjir daerah Ketintang',
            'description' => 'Terjadi banjir setinggi lutut sejak pagi hari',
            'latitude' => -7.303,
            'longitude' => 112.732,
            'address' => 'Ketintang, Surabaya'
        ]);

        $this->assertDatabaseHas('reports', [
            'title' => 'Banjir daerah Ketintang',
            'status' => 'pending'
        ]);
    }

    #[Test]
    public function report_creation_fails_when_required_fields_are_missing()
    {
        $user = User::factory()->create([
            'role' => 'masyarakat'
        ]);

        $response = $this->actingAs($user)
            ->post(route('reports.store'), []);

        $response->assertSessionHasErrors([
            'title',
            'description',
            'latitude',
            'longitude'
        ]);
    }

    #[Test]
    public function newly_created_report_has_default_pending_status()
    {
        $user = User::factory()->create([
            'role' => 'masyarakat'
        ]);

        $this->actingAs($user)->post(route('reports.store'), [
            'title' => 'Banjir daerah Wonokromo',
            'description' => 'Air meluap ke jalan utama',
            'latitude' => -7.291,
            'longitude' => 112.736,
        ]);

        $this->assertDatabaseHas('reports', [
            'status' => 'pending'
        ]);
    }

   #[Test]
public function user_cannot_view_other_users_report()
{
    $owner = User::factory()->create(['role' => 'masyarakat']);
    $otherUser = User::factory()->create(['role' => 'masyarakat']);

    $report = Report::create([
        'user_id' => $owner->id,
        'title' => 'Banjir daerah Rungkut',
        'description' => 'Air menggenang cukup tinggi',
        'latitude' => -7.320,
        'longitude' => 112.770,
        'address' => 'Rungkut, Surabaya',
        'status' => 'pending',
    ]);

    $this->actingAs($otherUser)
        ->get('/reports/'.$report->id)
        ->assertStatus(404); 
}

}
