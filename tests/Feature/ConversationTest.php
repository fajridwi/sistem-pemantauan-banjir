<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConversationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function masyarakat_can_send_message_to_government()
    {
        $masyarakat = User::factory()->create([
            'role' => 'masyarakat'
        ]);

        $pemerintah = User::factory()->create([
            'role' => 'pemerintah'
        ]);

        // Masyarakat membuka halaman chat (conversation dibuat otomatis)
        $this->actingAs($masyarakat)
            ->get('/chat/' . $pemerintah->id)
            ->assertStatus(200);

        $conversation = Conversation::first();

        // Masyarakat mengirim pesan
        $this->actingAs($masyarakat)
            ->post('/chat/' . $conversation->id, [
                'message' => 'Banjir daerah Ketintang sejak pagi'
            ]);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $masyarakat->id,
            'message' => 'Banjir daerah Ketintang sejak pagi'
        ]);
    }

    /** @test */
    public function government_can_reply_message_from_masyarakat()
    {
        $masyarakat = User::factory()->create([
            'role' => 'masyarakat'
        ]);

        $pemerintah = User::factory()->create([
            'role' => 'pemerintah'
        ]);

        // Conversation sudah ada
        $conversation = Conversation::create([
            'masyarakat_id' => $masyarakat->id,
            'pemerintah_id' => $pemerintah->id
        ]);

        // Pesan awal dari masyarakat
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $masyarakat->id,
            'message' => 'Banjir daerah Ketintang',
            'is_read' => false
        ]);

        // Pemerintah membalas pesan
        $this->actingAs($pemerintah)
            ->post('/chat/' . $conversation->id, [
                'message' => 'Laporan sedang kami proses'
            ]);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $pemerintah->id,
            'message' => 'Laporan sedang kami proses'
        ]);
    }
}
