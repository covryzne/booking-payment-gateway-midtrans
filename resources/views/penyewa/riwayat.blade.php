<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Booking</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <style>
    body {
        background: linear-gradient(135deg, #0f172a, #1e3a8a);
        color: white;
    }

    .card-custom {
        border-radius: 15px;
        background: white;
        color: black;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }
    </style>
</head>

<body>

    <nav class="navbar navbar-dark shadow-sm px-4" style="background: #0b1220;">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">
                <i class="fa fa-clock-rotate-left me-2"></i>
                Riwayat Booking Penyewa
            </span>

            <a href="/penyewa/dashboard" class="btn btn-outline-light btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </nav>

    <div class="container mt-4">

        <div class="card-custom p-4">
            <h5 class="mb-3">
                <i class="fa fa-list me-2"></i> Data Riwayat Booking
            </h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($bookings as $b)
                        <tr>
                            <td class="fw-bold text-primary">{{ $b->kode_booking }}</td>

                            <td>{{ $b->tanggal }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($b->jam_mulai)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($b->jam_selesai)->format('H:i') }}
                            </td>

                            <td>Rp {{ number_format($b->total_harga, 0, ',', '.') }}</td>

                            <td>
                                <span class="badge
                                    @if($b->status == 'pending') bg-warning text-dark
                                    @elseif($b->status == 'lunas') bg-success
                                    @else bg-secondary
                                    @endif">
                                    {{ $b->status == 'pending' ? 'Menunggu' : ucfirst($b->status) }}
                                </span>
                            </td>

                            <td class="text-center">
                                @if($b->status == 'pending')
                                @if($b->snap_token)
                                <button onclick="bayarSekarang('{{ $b->snap_token }}')" class="btn btn-warning btn-sm">
                                    <i class="fa fa-wallet"></i> Bayar
                                </button>
                                @else
                                <button class="btn btn-danger btn-sm" disabled>
                                    <i class="fa fa-exclamation-triangle"></i> Token Error
                                </button>
                                @endif
                                @endif

                                @if($b->status == 'lunas')
                                <a href="{{ route('booking.qr', $b->id) }}" class="btn btn-dark btn-sm">
                                    <i class="fa fa-qrcode"></i>
                                </a>
                                @else
                                @if($b->status != 'pending')
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="fa fa-qrcode"></i>
                                </button>
                                @endif
                                @endif
                            </td>
                        </tr>

                        <div class="modal fade" id="modalBayar{{ $b->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="fa fa-credit-card me-2"></i> Detail Pembayaran
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <p><strong>Kode Booking:</strong> {{ $b->kode_booking }}</p>

                                        <p>
                                            <strong>Total Biaya:</strong><br>
                                            <span class="fs-5 text-danger">
                                                Rp {{ number_format($b->total_harga, 0, ',', '.') }}
                                            </span>
                                        </p>

                                        <hr>

                                        <div class="alert alert-info mb-0">
                                            Pembayaran dilakukan via Midtrans Sandbox. Klik tombol Bayar untuk membuka
                                            popup pembayaran langsung di halaman ini.
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            Tutup
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada booking
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
    function bayarSekarang(snapToken) {
        snap.pay(snapToken, {
            onSuccess: function(result) {
                alert("Pembayaran berhasil!");
                window.location.reload();
            },
            onPending: function(result) {
                alert("Menunggu pembayaran! Silakan selesaikan di simulator.");
                window.location.reload();
            },
            onError: function(result) {
                alert("Pembayaran gagal!");
                window.location.reload();
            }
        });
    }
    </script>
</body>

</html>