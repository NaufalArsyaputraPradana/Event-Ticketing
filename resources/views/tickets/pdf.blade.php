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
        .ticket { width: calc(200mm - 12mm); height: calc(80mm - 12mm); border-radius: 12px; border: 2px solid #0f172a; overflow: hidden; }
        .wrap { width: 100%; height: 100%; }
        .left, .right { float: left; height: 100%; }

        /* Tema */
        .left { width: 72%; background: linear-gradient(135deg, #0b1220 0%, #0f1b33 100%); color: #fff; padding: 12px 16px; box-sizing: border-box; position: relative; }
        .right { width: 28%; background: #ffffff; border-left: 2px dashed #d1d5db; text-align: center; box-sizing: border-box; padding: 10px 8px; position: relative; }
        .brand { position: absolute; top: 8px; right: 12px; background: #f59e0b; color: #111827; font-size: 9px; font-weight: 800; padding: 4px 7px; border-radius: 9999px; }

        /* Judul & meta */
        .title { font-size: 18px; font-weight: 800; letter-spacing: .4px; margin: 0 0 6px; text-transform: uppercase; }
        .metas { margin-bottom: 10px; }
        .meta { display: inline-block; background: #f59e0b; color: #111827; font-weight: 800; font-size: 9px; padding: 4px 8px; border-radius: 6px; margin-right: 6px; }

        /* Box kecil ala mockup */
        .boxes { margin: 8px 0 12px; }
        .box { display: inline-block; min-width: 58px; padding: 6px 8px; margin-right: 6px; border: 2px solid #fbbf24; border-radius: 8px; text-align: center; background: rgba(245, 158, 11, 0.06); }
        .box-label { display: block; font-size: 8px; letter-spacing: .6px; color: #fbbf24; text-transform: uppercase; margin-bottom: 3px; }
        .box-value { display: block; font-size: 12px; font-weight: 800; color: #ffffff; }

        /* Info customer */
        .list { font-size: 10px; line-height: 1.5; }
        .list-row { margin-bottom: 3px; }
        .list b { color: #fbbf24; }

        /* QR Stub */
        .stub-title { font-size: 9px; color: #6b7280; text-transform: uppercase; letter-spacing: .6px; margin: 4px 0 6px; }
        .qr { width: 110px; height: 110px; margin: 0 auto 6px; border: 2px solid #e5e7eb; border-radius: 8px; object-fit: contain; }
        .code { font-size: 10px; color: #111827; font-weight: 700; }
        .small { font-size: 9px; color: #6b7280; margin-top: 2px; }

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
