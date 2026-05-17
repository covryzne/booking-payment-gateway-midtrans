<h2 style="text-align:center;">Laporan Penyewaan Tahun {{ $tahun }}</h2>

<table border="1" width="100%" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bookings as $b)
        <tr>
            <td>{{ $b->kode_booking }}</td>
            <td>{{ $b->nama }}</td>
            <td>{{ $b->no_hp }}</td>
            <td>{{ $b->alamat }}</td>
            <td>{{ $b->tanggal }}</td>
            <td>
                {{ \Carbon\Carbon::parse($b->jam_mulai)->format('H:i') }} -
                {{ \Carbon\Carbon::parse($b->jam_selesai)->format('H:i') }}
            </td>
            <td>Rp {{ number_format($b->total_harga) }}</td>
            <td>{{ ucfirst($b->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
