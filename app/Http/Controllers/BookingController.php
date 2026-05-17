<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Pagination\Paginator;


class BookingController extends Controller
{
    public function dashboard()
    {
        $bookings = Booking::all(); // semua booking (biar semua penyewa lihat)

        return view('penyewa.dashboard', compact('bookings'));
    }

    public function index()
    {
        $bookings = Booking::orderBy('status', 'asc')->get();
        return view('admin.booking', compact('bookings'));
    }

    public function create(Request $request)
    {
        $tanggal = $request->tanggal; // ambil dari URL

        return view('penyewa.create', compact('tanggal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'keperluan' => 'required|string',
        ], ['required' => ':kolom wajib diisi']);

        $day = date('N', strtotime($request->tanggal));

        $durasi = $request->durasi;
        $durasi = (int) $request->durasi;

        $jamMulai = Carbon::createFromFormat(
            'Y-m-d H:i',
            $request->tanggal . ' ' . $request->jam_mulai
        )->second(0);

        $jamSelesai = $jamMulai->copy()->addHours($durasi);

        $tanggalPilihan = Carbon::parse($request->tanggal)->format('Y-m-d');

        $hariIniLokal = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $jamSekarangLokal = Carbon::now('Asia/Jakarta')->format('H:i');

        if ($tanggalPilihan === $hariIniLokal) {
            $jamMulaiUser = Carbon::parse($request->jam_mulai)->format('H:i');

            if ($jamMulaiUser < $jamSekarangLokal) {
                return back()->withErrors([
                    'jam_mulai' => 'Tidak bisa memilih jam yang sudah lewat dari jam sekarang (' . $jamSekarangLokal . ' WIB)'
                ])->withInput();
            }
        } elseif ($tanggalPilihan < $hariIniLokal) {
            return back()->withErrors([
                'jam_mulai' => 'Tidak bisa memilih tanggal yang sudah lewat!'
            ])->withInput();
        }

        $hargaPerJam = 50000;
        $totalHarga = $durasi * $hargaPerJam;
        if ($durasi < 1) {
            return back()->withErrors([
                'durasi' => 'Durasi minimal 1 jam'
            ]);
        }

        $kodeBooking = 'BK-' . strtoupper(Str::random(8));
        $banks = ['BCA', 'BRI', 'BNI', 'MANDIRI'];
        $bank = $banks[array_rand($banks)];
        $va = '8808' . rand(10000000, 99999999);

        $start = Carbon::createFromTime(19, 0, 0);
        $end = Carbon::createFromTime(23, 0, 0);

        if (in_array($day, [2, 5])) {
            if ($jamMulai->between($start, $end) || $jamSelesai->between($start, $end)) {
                return back()->withErrors([
                    'jam' => 'Jam 19:00 - 23:00 tidak tersedia (latihan rutin Selasa & Jumat)'
                ])->withInput();
            }
        }

        $exists = Booking::where('tanggal', $request->tanggal)
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                $query->whereBetween('jam_mulai', [
                    $jamMulai->format('H:i:s'),
                    $jamSelesai->format('H:i:s')
                ])
                    ->orWhereBetween('jam_selesai', [
                        $jamMulai->format('H:i:s'),
                        $jamSelesai->format('H:i:s')
                    ])
                    ->orWhere(function ($q) use ($jamMulai, $jamSelesai) {
                        $q->where('jam_mulai', '<=', $jamMulai->format('H:i:s'))
                            ->where('jam_selesai', '>=', $jamSelesai->format('H:i:s'));
                    });
            })
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'jam' => 'Jam yang dipilih sudah dibooking oleh penyewa lain'
            ])->withInput();
        }

        Booking::create([
            'user_id' => Auth::id(),
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $jamSelesai->format('H:i:s'),
            'keperluan' => $request->keperluan,
            'durasi_jam' => (int)$request->durasi,
            'total_harga' => $totalHarga,
            'kode_booking' => $kodeBooking,
            'va_number' => $va,
            'bank' => $bank,
            'status' => 'pending',
        ]);

        return redirect()->route('penyewa.dashboard')->with('success', 'Booking berhasil');
    }

    public function riwayat()
    {
        $bookings = Booking::where('user_id', auth()->id())->get();

        // Generate Snap Token buat bokingan yang statusnya masih pending
        foreach ($bookings as $b) {
            if ($b->status == 'pending') {
                $params = [
                    'transaction_details' => [
                        // Tambahin buntut time() biar gak error 400 order_id duplikat saat di-refresh
                        'order_id' => $b->kode_booking . '-' . time(),
                        'gross_amount' => $b->total_harga,
                    ],
                    'customer_details' => [
                        'first_name' => $b->nama,
                        'phone' => $b->no_hp,
                    ],
                ];

                try {
                    // Simpan token snap ke dalam object dynamically biar bisa dipanggil di blade
                    $b->snap_token = Snap::getSnapToken($params);
                } catch (\Exception $e) {
                    $b->snap_token = null;
                }
            }
        }

        return view('penyewa.riwayat', compact('bookings'));
    }

    public function bayar($id)
    {
        $booking = Booking::whereKey($id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($booking->status === 'lunas') {
            return redirect()->route('penyewa.riwayat')
                ->with('success', 'Booking ini sudah lunas');
        }

        $this->configureMidtrans();

        $orderId = $booking->kode_booking . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->total_harga,
            ],
            'customer_details' => [
                'first_name' => $booking->nama,
                'phone' => $booking->no_hp,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('penyewa.bayar', compact('booking', 'snapToken'));
    }

    public function simulasiBayar($id)
    {
        $booking = Booking::findOrFail($id);

        $this->markBookingAsLunas($booking, 'BCA');

        return redirect()->route('penyewa.riwayat')
            ->with('success', 'Pembayaran berhasil (Simulasi)');
    }

    public function updateStatus($id)
    {
        $booking = Booking::findOrFail($id);

        // generate QR
        $qr = QrCode::format('svg')
            ->size(300)
            ->generate($booking->kode_booking);

        $fileName = 'qr/' . $booking->kode_booking . '.svg';
        Storage::put('public/' . $fileName, $qr);

        $booking->update([
            'status' => 'lunas',
            'qr_code' => $fileName
        ]);

        return response()->json(['success' => true]);
    }

    public function qr($id)
    {
        $booking = Booking::findOrFail($id);

        // hanya boleh kalau sudah lunas
        if ($booking->status != 'lunas') {
            return back()->with('error', 'QR hanya tersedia setelah pembayaran lunas');
        }

        return view('penyewa.qr', compact('booking'));
    }

    public function petugas()
    {
        Paginator::useBootstrap();

        $bookings = Booking::where('status', 'lunas')
            ->orderBy('is_used', 'asc')
            ->orderBy('tanggal', 'asc')
            ->paginate(5);

        return view('petugas.dashboard', compact('bookings'));
    }

    public function validateQr(Request $request)
    {
        $kode = $request->kode_booking;

        $booking = Booking::where('kode_booking', $kode)->first();

        // Tidak ditemukan
        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode tidak ditemukan'
            ]);
        }

        // Belum bayar
        if ($booking->status != 'lunas') {
            return response()->json([
                'status' => 'error',
                'message' => 'Belum lunas'
            ]);
        }

        // Bukan hari ini
        if ($booking->tanggal != date('Y-m-d')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bukan jadwal hari ini'
            ]);
        }

        // Sudah dipakai
        if ($booking->is_used) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR sudah digunakan'
            ]);
        }

        // Tandai sudah dipakai
        $booking->update([
            'is_used' => true
        ]);

        return response()->json([
            'status' => 'success',
            'nama' => $booking->nama,
            'kode' => $booking->kode_booking,
            'tanggal' => $booking->tanggal
        ]);
    }

    public function midtransNotification(Request $request)
    {
        $this->configureMidtrans();

        $notification = new Notification();
        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? null;

        $orderParts = explode('-', $orderId);
        array_pop($orderParts);
        $kodeAsli = implode('-', $orderParts);

        $booking = Booking::where('kode_booking', $kodeAsli)->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === null || $fraudStatus === 'accept') {
                $this->markBookingAsLunas($booking, 'BCA');
            }
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'], true)) {
            $booking->update([
                'status' => 'gagal',
            ]);
        }

        return response()->json(['message' => 'OK']);
    }

    private function configureMidtrans(): void
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    private function markBookingAsLunas(Booking $booking, string $bank): void
    {
        $va = '8808' . rand(10000000, 99999999);

        $qr = QrCode::format('svg')
            ->size(300)
            ->generate($booking->kode_booking);

        $fileName = 'qr/' . $booking->kode_booking . '.svg';
        Storage::put('public/' . $fileName, $qr);

        $booking->update([
            'status' => 'lunas',
            'va_number' => $va,
            'bank' => $bank,
            'qr_code' => $fileName,
        ]);
    }

    public function laporan(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');

        $bookings = Booking::whereYear('tanggal', $tahun)
            ->where('status', 'lunas')
            ->get();

        $totalBooking = $bookings->count();
        $totalPenyewa = $bookings->unique('nama')->count();
        $totalPendapatan = $bookings->sum('total_harga');

        return view('admin.laporan', compact(
            'bookings',
            'tahun',
            'totalBooking',
            'totalPenyewa',
            'totalPendapatan'
        ));
    }

    public function laporanPdf($tahun)
    {
        $bookings = Booking::whereYear('tanggal', $tahun)
            ->where('status', 'lunas')
            ->get();

        $pdf = Pdf::loadView('admin.laporan_pdf', compact('bookings', 'tahun'));

        return $pdf->download('laporan-' . $tahun . '.pdf');
    }

    public function downloadQrPng($id)
    {
        $booking = Booking::findOrFail($id);

        $url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . $booking->kode_booking;

        return redirect($url);
    }
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->delete();

        return back()->with('success', 'Data booking berhasil dihapus');
    }
}
