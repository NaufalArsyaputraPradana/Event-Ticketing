<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;

class GenerateQrCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qrcode:generate {ticket_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate QR code for a ticket';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ticketId = $this->argument('ticket_id');
        
        if ($ticketId) {
            $ticket = Ticket::find($ticketId);
            if (!$ticket) {
                $this->error("Ticket dengan ID {$ticketId} tidak ditemukan.");
                return 1;
            }
            $this->generateQrCodeForTicket($ticket);
        } else {
            $tickets = Ticket::whereNull('qr_code')->get();
            if ($tickets->isEmpty()) {
                $this->info('Semua ticket sudah memiliki QR code.');
                return 0;
            }
            
            $this->info("Generating QR code untuk {$tickets->count()} ticket...");
            foreach ($tickets as $ticket) {
                $this->generateQrCodeForTicket($ticket);
            }
        }
        
        $this->info('QR code generation selesai!');
        return 0;
    }
    
    private function generateQrCodeForTicket(Ticket $ticket)
    {
        try {
            $this->info("Generating QR code untuk ticket: {$ticket->ticket_code}");
            
            // Generate QR code data
            $data = json_encode([
                'ticket_code' => $ticket->ticket_code,
                'event_id' => $ticket->event_id,
                'booking_id' => $ticket->booking_id,
                'customer_name' => $ticket->customer_name,
                'customer_email' => $ticket->customer_email,
                'ticket_number' => $ticket->ticket_number
            ]);
            
            // Use external QR code API
            $externalQrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($data);
            $ticket->update(['qr_code' => $externalQrUrl]);
            
            $this->info("✓ QR code berhasil dibuat untuk ticket: {$ticket->ticket_code}");
            
        } catch (\Exception $e) {
            $this->error("✗ Error generating QR code untuk ticket {$ticket->ticket_code}: " . $e->getMessage());
        }
    }
}
