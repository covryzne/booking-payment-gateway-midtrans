<!DOCTYPE html>
<html>

<head>
    <title>Pembayaran</title>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>

<body>

    <h3>Bayar Booking: {{ $booking->kode_booking }}</h3>
    <h4>Total: Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</h4>

    <p>Status saat ini: {{ ucfirst($booking->status) }}</p>

    <button id="pay-button">Bayar Sekarang</button>

    <script>
        document.getElementById('pay-button').onclick = function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    alert("Pembayaran berhasil!");
                    window.location.href = "/penyewa/riwayat";
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran!");
                    window.location.href = "/penyewa/riwayat";
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                    window.location.href = "/penyewa/riwayat";
                }
            });
        };
    </script>

</body>

</html>