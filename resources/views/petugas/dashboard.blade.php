<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Petugas</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- QR Scanner -->
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            color: white;
        }

        .card-custom {
            border-radius: 15px;
            background: white;
            color: black;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        #reader {
    position: relative;
    z-index: 1;
}

.btn-validasi {
    position: relative;
    z-index: 10;
}
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm px-4" style="background: #0b1220;">
    <div class="container-fluid">

        <span class="navbar-brand fw-bold">
            <i class="fa-solid fa-user-gear me-2"></i>
            Dashboard Petugas
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger btn-sm px-3">
                <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
            </button>
        </form>

    </div>
</nav>

<div class="container mt-4">

    <h3 class="mb-4">Selamat Datang, {{ auth()->user()->name }} 👋</h3>

    <div class="row">

        <!-- 🔍 SEARCH -->
        <div class="col-md-6">
            <div class="card-custom p-3 mb-4">
                <h5><i class="fa-solid fa-magnifying-glass"></i> Cari Booking</h5>

                <input type="text" id="search" class="form-control mt-2" placeholder="Masukkan kode booking...">
            </div>
        </div>

        <!-- 📷 SCANNER -->
        <div class="col-md-6">
            <div class="card-custom p-3 mb-4">
                <h5><i class="fa-solid fa-qrcode"></i> Scan Barcode</h5>
                <div id="reader"></div>

        <button id="startScan" class="btn btn-success w-100 mt-2">
    ▶️ Mulai Scan
</button>

<button id="stopScan" class="btn btn-danger w-100 mt-2">
    ⛔ Stop Scan
</button>

        <button id="btnFoto" class="btn btn-primary mt-3 w-100">
            📸 Ambil Foto QR
        </button>

        <input type="file" id="fileInput" accept="image/*" hidden>
            </div>
        </div>

        <div class="col-md-12">
        <div class="card-custom p-3 mb-4">
            <h5><i class="fa-solid fa-file-pdf"></i> Upload PDF E-Ticket</h5>

            <input type="file" id="uploadPdf" class="form-control mt-2" accept="application/pdf">
            <small class="text-muted">Upload PDF untuk ambil kode booking</small>
        </div>
</div>

    </div>

    <!-- 📋 TABLE BOOKING -->
    <div class="card-custom p-3">
        <h5><i class="fa-solid fa-list"></i> Riwayat Booking</h5>

        <table class="table table-striped mt-3" id="bookingTable">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Status</th>
                    <th>QR Code</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
@foreach($bookings as $b)
<tr>
    <td>{{ $b->kode_booking }}</td>
    <td>{{ $b->nama }}</td>
    <td>{{ $b->tanggal }}</td>
    <td>
        {{ \Carbon\Carbon::parse($b->jam_mulai)->format('H:i') }} -
        {{ \Carbon\Carbon::parse($b->jam_selesai)->format('H:i') }}
    </td>
    <td>
        @if($b->is_used)
            <span class="badge bg-secondary">Sudah Digunakan</span>
        @else
            <span class="badge bg-success">Lunas</span>
        @endif
    </td>
    <td>
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $b->kode_booking }}">
    </td>
    <td>
    @if(!$b->is_used)

    @if(\Carbon\Carbon::parse($b->tanggal)->isToday())
        <button class="btn btn-success btn-sm btn-validasi"
            data-kode="{{ $b->kode_booking }}">
            ✔ Validasi
        </button>
    @else
        <button class="btn btn-warning btn-sm" disabled>
            ⏳ Belum Hari H
        </button>
    @endif

@else
    <button class="btn btn-secondary btn-sm" disabled>
        ✔ Sudah Digunakan
    </button>
@endif
</td>
</tr>
@endforeach
</tbody>
        </table>
    </div>

</div>

<!-- SCRIPT -->
<script>
let html5QrCode;
let scanned = false;

// ================== KIRIM KODE ==================
function kirimKode(kode) {
    console.log("TERKIRIM:", kode);

    fetch("{{ route('petugas.scan') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            kode_booking: kode
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert(`✅ VALID
Nama: ${data.nama}
Kode: ${data.kode}
Tanggal: ${data.tanggal}`);

            location.reload();
        } else {
            alert("❌ " + data.message);
        }
    });
}

// ================== SEARCH ==================
document.addEventListener("DOMContentLoaded", function () {

    document.getElementById('search').addEventListener('keyup', function () {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("#bookingTable tbody tr");

        rows.forEach(row => {
            let kode = row.children[0].innerText.toLowerCase();

            if (kode.includes(value)) {
                row.style.display = "";
                row.classList.add("table-warning");
            } else {
                row.style.display = "none";
                row.classList.remove("table-warning");
            }
        });

        if (value.length >= 5) {
            kirimKode(value.toUpperCase());
        }
    });

    // ================== INIT SCANNER ==================
    html5QrCode = new Html5Qrcode("reader");

    // ================== START SCAN ==================
    document.getElementById("startScan").addEventListener("click", () => {
        scanned = false;

        html5QrCode.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            onScanSuccess
        ).catch(err => {
            console.log("Camera error:", err);
        });
    });

    // ================== STOP SCAN ==================
    document.getElementById("stopScan").addEventListener("click", () => {
        try {
            html5QrCode.stop();
        } catch (e) {}
    });

    // ================== VALIDASI BUTTON ==================
    document.querySelectorAll('.btn-validasi').forEach(btn => {
        btn.addEventListener('click', function () {

            let kode = this.dataset.kode;

            console.log("Klik tombol:", kode);

            // stop kamera kalau aktif
            try {
                html5QrCode.stop();
            } catch (e) {}

            kirimKode(kode);
        });
    });

});

// ================== SCAN SUCCESS ==================
function onScanSuccess(decodedText) {
    if (scanned) return;
    scanned = true;

    try {
        html5QrCode.stop();
    } catch (e) {}

    kirimKode(decodedText);
}

// ================== FOTO QR ==================
const fileInput = document.getElementById('fileInput');
const btnFoto = document.getElementById('btnFoto');

btnFoto.addEventListener('click', () => {
    fileInput.click();
});

fileInput.addEventListener('change', e => {
    const file = e.target.files[0];
    if (!file) return;

    try {
        html5QrCode.stop();
    } catch (e) {}

    html5QrCode.scanFile(file, true)
        .then(decodedText => {
            console.log("SCAN FOTO:", decodedText);
            kirimKode(decodedText);
        })
        .catch(() => {
            alert("❌ QR tidak terdeteksi dari foto, gunakan manual");
        });
});
</script>

<div class="d-flex justify-content-center mt-3">
    {{ $bookings->links() }}
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
document.getElementById('uploadPdf').addEventListener('change', function(e) {

    let file = e.target.files[0];
    if (!file) return;

    let reader = new FileReader();

    reader.onload = function() {
        let typedarray = new Uint8Array(this.result);

        pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {

            pdf.getPage(1).then(function(page) {

                page.getTextContent().then(function(textContent) {

                    let text = textContent.items.map(item => item.str).join(" ");

                    console.log("ISI PDF:", text);

                    // Ambil kode booking (format BK-XXXX)
                    let match = text.match(/BK-[A-Z0-9]+/);

                    if (match) {
                        kirimKode(match[0]);
                    } else {
                        let manual = prompt("Kode tidak ditemukan.\nMasukkan kode booking manual:");
                        if (manual) {
                            kirimKode(manual.toUpperCase());
                        }
                    }

                });

            });

        });

    };

    reader.readAsArrayBuffer(file);
});
</script>

</body>
</html>
