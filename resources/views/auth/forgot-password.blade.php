<x-guest-layout>

    <h4 class="text-center mb-3 text-dark fw-bold">
        Reset Password
    </h4>

    <p class="text-center mb-3 text-muted" style="font-size: 14px;">
        Masukkan email kamu untuk mendapatkan link reset password
    </p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-3 text-success text-center" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Email :</label>
            <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
            <x-input-error :messages="$errors->get('email')" class="text-danger small" />
        </div>

        <!-- Button -->
        <button class="btn w-100 fw-bold text-white" style="background: linear-gradient(45deg, #000, #0d47a1);">
            <i class="fa-solid fa-paper-plane"></i> Kirim Link Reset
        </button>

        <!-- Back to Login -->
        <p class="text-center mt-3">
            Kembali ke
            <a href="{{ route('login') }}" class="fw-bold text-primary">
                Login
            </a>
        </p>

    </form>

</x-guest-layout>
