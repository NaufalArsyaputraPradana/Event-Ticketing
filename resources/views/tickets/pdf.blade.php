<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - {{ $ticket->event->title }}</title>
    <style>
        /* Halaman kecil memanjang: 200mm x 80mm */
        @page { size: 200mm 80mm; margin: 6mm; }
        html, body { margin: 0; padding: 0; font-family: Arial, sans-serif; color: #111827; }

        /* Ukuran kontainer diset pasti agar tidak pecah halaman */
        .ticket { width: calc(200mm - 12mm); height: calc(80mm - 12mm); border-radius: 14px; background: #f5b000; /* kuning keemasan */ padding: 6px; box-sizing: border-box; }
        .wrap { width: 100%; height: 100%; background: #0b0b0b; border-radius: 10px; overflow: hidden; }
        .left, .right { float: left; height: 100%; }

        /* Tema */
        .left { width: 72%; background: #0b0b0b; color: #fff; padding: 12px 16px; box-sizing: border-box; position: relative; }
        .right { width: 28%; background: #ffffff; border-left: 2px dashed #e5e7eb; text-align: center; box-sizing: border-box; padding: 10px 8px; position: relative; }
        .brand { position: absolute; top: 8px; right: 12px; background: #f5b000; color: #111827; font-size: 9px; font-weight: 800; padding: 4px 7px; border-radius: 9999px; box-shadow: 0 0 0 2px #111 inset; }

        /* Judul & meta */
        .title { font-size: 36px; font-weight: 900; letter-spacing: .8px; margin: 0 0 12px; text-transform: uppercase; color: #ffffff; }
        .metas { margin-bottom: 10px; }
        .meta { display: inline-block; background: #f5b000; color: #111827; font-weight: 800; font-size: 18px; padding: 6px 10px; border-radius: 8px; margin-right: 8px; }

        /* Box kecil ala mockup */
        .boxes { margin: 8px 0 12px; }
        .box { display: inline-block; min-width: 58px; padding: 6px 8px; margin-right: 6px; border: 2px solid #f5b000; border-radius: 8px; text-align: center; background: #111111; }
        .box-label { display: block; font-size: 16px; letter-spacing: .6px; color: #f5b000; text-transform: uppercase; margin-bottom: 4px; }
        .box-value { display: block; font-size: 24px; font-weight: 800; color: #ffffff; }

        /* Info customer */
        .list { font-size: 18px; line-height: 1.6; }
        .list-row { margin-bottom: 4px; }
        .list b { color: #f5b000; }

        /* QR Stub */
        .stub-title { font-size: 16px; color: #6b7280; text-transform: uppercase; letter-spacing: .6px; margin: 6px 0 10px; }
        .qr { width: 200px; height: 200px; margin: 0 auto 10px; border: 3px solid #111827; border-radius: 10px; object-fit: contain; box-shadow: 0 0 0 3px #f5b000 inset; }
        .code { font-size: 18px; color: #111827; font-weight: 700; }
        .small { font-size: 16px; color: #6b7280; margin-top: 4px; }

        /* Perforation dots visual */
        .dots { position: absolute; left: -7px; top: 0; bottom: 0; width: 14px; background: repeating-linear-gradient(to bottom, transparent, transparent 6px, rgba(17,24,39,.15) 6px, rgba(17,24,39,.15) 10px); }

        /* Clearfix */
        .clearfix::after { content: ""; display: table; clear: both; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="wrap clearfix">
            <!-- Panel kiri: informasi -->
            <div class="left">
                <span class="brand">EVENT TICKET</span>
                <h1 class="title">{{ strtoupper($ticket->event->title) }}</h1>
                <div class="metas">
                    <span class="meta">{{ $ticket->event->event_date->format('d M Y') }}</span>
                    <span class="meta">{{ $ticket->event->event_date->format('H:i') }}</span>
                </div>
                <div class="boxes">
                    <div class="box"><span class="box-label">Kode</span><span class="box-value">{{ $ticket->ticket_code }}</span></div>
                    <div class="box"><span class="box-label">Nomor</span><span class="box-value">{{ $ticket->formatted_ticket_number }}</span></div>
                    <div class="box"><span class="box-label">Status</span><span class="box-value">{{ strtoupper($ticket->status) }}</span></div>
                </div>
                <div class="list">
                    <div class="list-row"><b>Nama:</b> {{ $ticket->customer_name }}</div>
                    <div class="list-row"><b>Email:</b> {{ $ticket->customer_email }}</div>
                    @if($ticket->customer_phone)
                        <div class="list-row"><b>Telepon:</b> {{ $ticket->customer_phone }}</div>
                    @endif
                    <div class="list-row"><b>Venue:</b> {{ $ticket->event->venue }}</div>
                    <div class="list-row"><b>Kode Booking:</b> {{ $ticket->booking->booking_code }}</div>
                    <div class="list-row"><b>Harga:</b> Rp {{ number_format($ticket->event->price, 0, ',', '.') }}</div>
                    <div class="list-row small">Tunjukkan tiket ini saat check-in.</div>
                </div>
            </div>

            <!-- Panel kanan: QR stub -->
            <div class="right">
                <div class="dots"></div>
                <p class="stub-title">QR Code</p>
                @if($ticket->qr_code_url)
                    <img src="{{ $ticket->qr_code_url }}" class="qr" alt="QR Code">
                @else
                    <div class="qr" style="display:flex;align-items:center;justify-content:center;color:#9ca3af;">QR</div>
                @endif
                <div class="code">{{ $ticket->formatted_ticket_number }}</div>
                <div class="code">{{ strtoupper($ticket->event->title) }}</div>
            </div>
        </div>
    </div>
</body>
</html>
