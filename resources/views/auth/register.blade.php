<x-guest-layout>

    <h4 class="text-center mb-3 text-dark fw-bold">
        Registrasi Akun
    </h4>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Nama :</label>
            <input type="text" name="name" class="form-control" placeholder="Masukkan nama" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Email :</label>
            <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
        </div>

        <div class="mb-3 position-relative">
    <label class="form-label fw-semibold">Password :</label>

    <input type="password" id="reg_password" name="password"
           class="form-control pe-5" required>

    <span onclick="togglePassword('reg_password', this)"
          style="position: absolute; right: 15px; top: 38px; cursor: pointer;">
        <i class="fa-solid fa-eye"></i>
    </span>
</div>

        <div class="mb-3 position-relative">
    <label class="form-label fw-semibold">Konfirmasi Password :</label>

    <input type="password" id="confirm_password" name="password_confirmation"
           class="form-control pe-5" required>

    <span onclick="togglePassword('confirm_password', this)"
          style="position: absolute; right: 15px; top: 38px; cursor: pointer;">
        <i class="fa-solid fa-eye"></i>
    </span>
</div>

        <button class="btn w-100 fw-bold text-white" style="background: linear-gradient(45deg, #0d47a1, #000);">
            <i class="fa-solid fa-user-plus"></i> Register
        </button>

        <p class="text-center mt-3">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="fw-bold text-primary">
                Login
            </a>
        </p>

    </form>

</x-guest-layout>
