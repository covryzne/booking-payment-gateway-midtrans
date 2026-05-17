<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket Booking</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f5f5f5;">

<div class="container mt-5">

    <div class="card shadow p-4 text-center">

        <h4 class="mb-3">E-Ticket Booking GOR</h4>

        <p><strong>Kode:</strong> {{ $booking->kode_booking }}</p>
        <p>
    {{ $booking->tanggal }} |
    {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }}
    -
    {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
</p>

        <div class="my-4">
            {!! QrCode::size(200)->generate($booking->kode_booking) !!}
        </div>

        <p class="text-muted">Tunjukkan QR ini saat hari H</p>

        <div class="d-flex justify-content-center gap-2">
        <button onclick="window.print()" class="btn btn-primary">
            Download / Print
        </button>

        <a href="{{ route('penyewa.download.qr', $booking->id) }}"
        class="btn btn-success">
        ⬇️ Download QR (PNG)
        </a>

<script>
function forceDownload(e, url) {
    e.preventDefault();
    const link = document.createElement('a');
    link.href = url;
    link.download = '';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

    <a href="{{ route('penyewa.riwayat') }}" class="btn btn-secondary">
        ← Kembali
    </a>
</div>


    </div>

</div>

</body>
</html>
