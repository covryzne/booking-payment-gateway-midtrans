<x-guest-layout>

    <h4 class="text-center mb-3 text-dark fw-bold">
        Reset Password
    </h4>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- TOKEN -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- EMAIL -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Email :</label>
            <input type="email" name="email"
                   class="form-control"
                   value="{{ old('email', $request->email) }}"
                   required>
        </div>

        <!-- PASSWORD -->
        <div class="mb-3 position-relative">
            <label class="form-label fw-semibold">Password Baru :</label>

            <input type="password" id="password"
                   name="password"
                   class="form-control pe-5"
                   required>

            <span onclick="togglePassword('password', this)"
                  style="position: absolute; right: 15px; top: 38px; cursor: pointer;">
                <i class="fa-solid fa-eye"></i>
            </span>
        </div>

        <!-- KONFIRMASI -->
        <div class="mb-3 position-relative">
            <label class="form-label fw-semibold">Konfirmasi Password :</label>

            <input type="password" id="password_confirmation"
                   name="password_confirmation"
                   class="form-control pe-5"
                   required>

            <span onclick="togglePassword('password_confirmation', this)"
                  style="position: absolute; right: 15px; top: 38px; cursor: pointer;">
                <i class="fa-solid fa-eye"></i>
            </span>
        </div>

        <!-- BUTTON -->
        <button class="btn w-100 fw-bold text-white"
                style="background: linear-gradient(45deg, #0d47a1, #000);">
            <i class="fa-solid fa-key"></i> Reset Password
        </button>

        <!-- BACK -->
        <p class="text-center mt-3">
            Kembali ke
            <a href="{{ route('login') }}" class="fw-bold text-primary">
                Login
            </a>
        </p>

    </form>

    <!-- SCRIPT TOGGLE PASSWORD -->
    <script>
        function togglePassword(id, el) {
            let input = document.getElementById(id);
            let icon = el.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>

</x-guest-layout>
