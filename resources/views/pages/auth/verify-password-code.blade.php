<x-auth-layout>
    <form class="form w-100" method="POST" action="{{ url('/verify-password-code') }}">
        @csrf
        <input type="hidden" name="email" value="{{ old('email', $email) }}">

        <div class="text-center mb-10">
            <h1 class="text-gray-900 fw-bolder mb-3">Verify Email</h1>
            <div class="text-gray-500 fw-semibold fs-6">
                Enter the 4-digit code sent to<br>
                <strong>{{ old('email', $email) }}</strong>
            </div>
        </div>

        <div class="fv-row mb-8">
            <input type="text" name="code" inputmode="numeric" maxlength="4" pattern="[0-9]{4}"
                autocomplete="one-time-code" placeholder="4-digit code"
                class="form-control bg-transparent text-center fs-2 letter-spacing-code"
                value="{{ old('code') }}" autofocus required>
            @error('code')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid mb-5">
            <button type="submit" class="btn btn-primary">Verify Code</button>
        </div>

        <div class="text-center">
            <a href="{{ route('password.request', ['email' => $email]) }}" class="link-primary">Send code again</a>
        </div>
    </form>

    <style>
        .letter-spacing-code { letter-spacing: 0.6rem; }
    </style>
</x-auth-layout>