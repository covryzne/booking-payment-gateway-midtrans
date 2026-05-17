<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Admin</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
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

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark px-4" style="background:#0b1220;">
        <span class="navbar-brand fw-bold">
            <i class="fa fa-chart-line me-2"></i> Laporan Booking Admin
        </span>

        <a href="/admin/dashboard" class="btn btn-outline-light btn-sm">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </nav>

    <div class="container mt-4">

        <!-- FILTER -->
        <div class="card-custom p-4 mb-4">
            <h5 class="mb-3">
                <i class="fa fa-filter me-2"></i> Filter Laporan
            </h5>

            <form method="GET" action="{{ route('admin.laporan') }}">
                <div class="row">

                    <div class="col-md-4 position-relative">

                        <select name="tahun" class="form-control pe-5">
                            @for($i = 2017; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>
                                {{ $i }}
                                </option>
                                @endfor
                        </select>

                        <!-- ICON PANAH -->
                        <i class="fa fa-chevron-down position-absolute"
                            style="right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none;">
                        </i>

                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary w-100">
                            <i class="fa fa-search"></i> Tampilkan
                        </button>
                    </div>

                    <div class="col-md-3">
                        <a href="{{ route('admin.laporan.pdf', $tahun) }}" class="btn btn-danger w-100">
                            <i class="fa fa-file-pdf"></i> Download PDF
                        </a>
                    </div>

                </div>
            </form>
        </div>

        <!-- TABEL -->
        <div class="card-custom p-4">

            <h5 class="mb-3">
                <i class="fa fa-list me-2"></i> Data Tahun {{ $tahun }}
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Tidak ada data laporan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

    </div>

</body>

</html>