<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Booking Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

    <nav class="navbar navbar-dark px-4" style="background:#0b1220;">
        <span class="navbar-brand fw-bold">
            <i class="fa fa-calendar-check me-2"></i> Data Booking Admin
        </span>

        <a href="/admin/dashboard" class="btn btn-outline-light btn-sm">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </nav>

    <div class="container mt-4">
        <div class="card-custom p-4">

            <h5 class="mb-3">
                <i class="fa fa-list me-2"></i> Semua Booking
            </h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($bookings as $b)
                        <tr>
                            <td class="fw-bold text-primary">{{ $b->kode_booking }}</td>
                            <td>{{ $b->nama }}</td>
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
                            @endif
                        ">
                                    {{ $b->status == 'pending' ? 'Menunggu' : ucfirst($b->status) }}
                                </span>
                            </td>

                            <td>
                                <form action="{{ route('admin.booking.delete', $b->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Tidak ada data booking
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                </table>
            </div>

        </div>
    </div>

</body>

</html>