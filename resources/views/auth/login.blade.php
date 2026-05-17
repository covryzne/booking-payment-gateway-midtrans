<x-guest-layout>

    <h4 class="text-center mb-3 text-dark fw-bold">
        Login
    </h4>

    <x-auth-session-status class="mb-3 text-success text-center" :status="session('status')" />

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Email :</label>
            <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
            <x-input-error :messages="$errors->get('email')" class="text-danger small" />
        </div>

        <!-- Password -->
        <div class="mb-3 position-relative">
            <label class="form-label fw-semibold">Password :</label>

            <input type="password" id="password" name="password"
                class="form-control pe-5" placeholder="Masukkan password" required>

            <span onclick="togglePassword('password', this)"
                style="position: absolute; right: 15px; top: 38px; cursor: pointer;">
                <i class="fa-solid fa-eye"></i>
            </span>

            <x-input-error :messages="$errors->get('password')" class="text-danger small" />
        </div>

        <!-- Remember -->
        <div class="d-flex justify-content-between mb-3">
            <div>
                <input type="checkbox" name="remember"> Ingat saya
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none text-primary">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Button -->
        <button class="btn w-100 fw-bold text-white" style="background: linear-gradient(45deg, #000, #0d47a1);">
            <i class="fa-solid fa-right-to-bracket"></i> Login
        </button>

        <!-- Register -->
        <p class="text-center mt-3">
            Belum punya akun?
            <a href="{{ route('register') }}" class="fw-bold text-primary">
                Daftar
            </a>
        </p>

    </form>

</x-guest-layout>
