<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Booking;
use App\Models\Ticket;
use App\Services\QrCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class QrCodeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function it_can_generate_qr_code_for_ticket()
    {
        // Create test data
        $user = User::factory()->create(['role' => 'admin']);
        $event = Event::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'payment_status' => 'paid'
        ]);
        $ticket = Ticket::factory()->create([
            'booking_id' => $booking->id,
            'event_id' => $event->id,
            'status' => 'active'
        ]);

        $qrCodeService = app(QrCodeService::class);
        $qrCodePath = $qrCodeService->generateTicketQrCode($ticket);

        // Assert QR code was generated and saved
        $this->assertNotEmpty($qrCodePath);
        $this->assertTrue(Storage::disk('public')->exists($qrCodePath));
        
        // Assert ticket was updated with QR code path
        $ticket->refresh();
        $this->assertEquals($qrCodePath, $ticket->qr_code);
    }

    /** @test */
    public function it_can_scan_ticket_via_api()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $event = Event::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'payment_status' => 'paid'
        ]);
        $ticket = Ticket::factory()->create([
            'booking_id' => $booking->id,
            'event_id' => $event->id,
            'status' => 'active'
        ]);

        $response = $this->actingAs($user)
            ->getJson("/tickets/scan/{$ticket->ticket_code}/api");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'ticket_code' => $ticket->ticket_code,
                    'event_title' => $event->title,
                    'customer_name' => $ticket->customer_name,
                    'status' => 'active',
                    'is_valid' => true
                ]
            ]);
    }

    /** @test */
    public function it_can_validate_ticket()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $event = Event::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'payment_status' => 'paid'
        ]);
        $ticket = Ticket::factory()->create([
            'booking_id' => $booking->id,
            'event_id' => $event->id,
            'status' => 'active'
        ]);

        $response = $this->actingAs($user)
            ->postJson("/tickets/validate/{$ticket->ticket_code}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Tiket berhasil divalidasi'
            ]);

        // Assert ticket status was updated
        $ticket->refresh();
        $this->assertEquals('used', $ticket->status);
        $this->assertNotNull($ticket->used_at);
    }

    /** @test */
    public function it_cannot_validate_already_used_ticket()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $event = Event::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'payment_status' => 'paid'
        ]);
        $ticket = Ticket::factory()->create([
            'booking_id' => $booking->id,
            'event_id' => $event->id,
            'status' => 'used'
        ]);

        $response = $this->actingAs($user)
            ->postJson("/tickets/validate/{$ticket->ticket_code}");

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Tiket sudah digunakan'
            ]);
    }

    /** @test */
    public function it_cannot_validate_inactive_ticket()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $event = Event::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'payment_status' => 'pending'
        ]);
        $ticket = Ticket::factory()->create([
            'booking_id' => $booking->id,
            'event_id' => $event->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($user)
            ->postJson("/tickets/validate/{$ticket->ticket_code}");

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Tiket tidak aktif'
            ]);
    }

    /** @test */
    public function it_returns_404_for_invalid_ticket_code()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)
            ->getJson("/tickets/scan/INVALID_CODE/api");

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Tiket tidak ditemukan'
            ]);
    }

    /** @test */
    public function it_requires_authentication_for_scanning()
    {
        $response = $this->getJson("/tickets/scan/TEST123/api");
        $response->assertStatus(401);
    }

    /** @test */
    public function it_requires_authentication_for_validation()
    {
        $response = $this->postJson("/tickets/validate/TEST123");
        $response->assertStatus(401);
    }
}
