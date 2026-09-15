<x-auth-layout>

    <!--begin::Form-->
    <form class="form w-100" method="POST" action="{{ route('password.email') }}" id="passwordRequestForm">
        @csrf
        <!--begin::Heading-->
        <div class="text-center mb-10">
            <!--begin::Title-->
            <h1 class="text-gray-900 fw-bolder mb-3">
                Forgot Password ?
            </h1>
            <!--end::Title-->

            <!--begin::Link-->
            <div class="text-gray-500 fw-semibold fs-6">
                Enter your email to reset your password.
            </div>
            <!--end::Link-->
        </div>
        <!--begin::Heading-->

        <!--begin::Input group--->
        <div class="fv-row mb-8">
            <!--begin::Email-->
            <input type="email" placeholder="Email" name="email" autocomplete="email" class="form-control bg-transparent" value="{{ old('email', request('email')) }}"/>
            <!--end::Email-->
        </div>

        <!--begin::Actions-->
        <div class="d-flex flex-wrap justify-content-center pb-lg-0">
            <button type="submit" class="btn btn-primary me-4" id="sendVerificationCode">
                <span class="send-code-label">Send Verification Code</span>
                <span class="send-code-spinner d-none" aria-hidden="true"></span>
            </button>

            <a href="{{ route('login') }}" class="btn btn-light">Cancel</a>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->

</x-auth-layout>

<style>
    #passwordRequestForm {
        position: absolute !important;
        inset: 0 !important;
        display: flex !important;
        flex-direction: column;
        justify-content: center;
        width: min(460px, calc(100vw - 48px)) !important;
        max-width: none !important;
        min-height: 100% !important;
        margin: auto !important;
    }

    #passwordRequestForm h1 {
        font-size: 26px !important;
    }

    #passwordRequestForm .text-gray-500 {
        font-size: 15px !important;
    }

    #passwordRequestForm input[name="email"] {
        width: 100% !important;
        height: 58px !important;
        padding: 0 18px !important;
        font-size: 15px !important;
        box-sizing: border-box;
    }

    #passwordRequestForm .d-flex {
        gap: 12px;
    }

    #passwordRequestForm button,
    #passwordRequestForm a.btn {
        min-height: 54px;
        padding: 13px 22px !important;
        font-size: 15px !important;
    }
</style>

<div class="otp-modal-backdrop d-none" id="passwordOtpModal" role="dialog" aria-modal="true">
    <div class="otp-modal">
        <button type="button" class="otp-close" id="closeOtpModal" aria-label="Close">&times;</button>
        <div class="otp-back">&#8249;</div>
        <h1>Sign in</h1>
        <div class="otp-protected">&#10003; &nbsp; Your information is protected</div>
        <p>Please enter the 4-digit code sent to<br><strong id="otpEmail"></strong></p>
        <a href="#" id="modifyOtpEmail">Modify email address</a>
        <div class="otp-boxes" id="otpBoxes">
            <input maxlength="1" inputmode="numeric" autocomplete="one-time-code" autofocus>
            <input maxlength="1" inputmode="numeric">
            <input maxlength="1" inputmode="numeric">
            <input maxlength="1" inputmode="numeric">
        </div>
        <div class="otp-error" id="otpError"></div>
        <button type="button" class="otp-resend" id="resendOtp">Resend code</button>
        <button type="button" class="otp-submit" id="verifyOtp">
            <span class="verify-otp-label">Sign in</span>
            <span class="verify-otp-spinner d-none" aria-hidden="true"></span>
        </button>
    </div>
</div>

<style>
    .otp-modal-backdrop { position: fixed; inset: 0; z-index: 1055; display: flex; align-items: stretch; justify-content: stretch; background: transparent; }
    .otp-modal-backdrop.d-none { display: none; }
    .otp-modal { position: relative; width: 100%; min-height: 100%; box-sizing: border-box; padding: 48px 70px 58px; background: #fff; text-align: center; box-shadow: none; border-radius: 0; }
    .otp-modal h1 { margin: 8px 0 20px; color: #071942; font-size: 24px; }
    .otp-modal p { color: #8d99ad; font-size: 16px; line-height: 1.35; }
    .otp-modal p strong { color: #111; font-size: 16px; }
    .otp-protected { color: #071942; font-size: 12px; margin-bottom: 8px; }
    .otp-protected:first-letter { color: #00aa6c; }
    .otp-modal a { display: inline-block; margin: 8px 0 28px; color: #1683f8; font-size: 16px; text-decoration: none; }
    .otp-close, .otp-back { display: none; }
    .otp-boxes { display: flex; justify-content: center; gap: 8px; }
    .otp-boxes input { width: 62px; height: 64px; border: 1px solid #cdd2d8; text-align: center; font-size: 28px; outline: none; }
    .otp-boxes input:focus { border: 2px solid #222; }
    .otp-resend { border: 0; background: transparent; color: #8d99ad; margin: 16px 0 36px; cursor: pointer; font-size: 14px; }
    .otp-submit { display: block; width: 100%; border: 0; padding: 13px; background: #d0d0d0; color: #fff; font-size: 20px; font-weight: 700; cursor: pointer; }
    .otp-submit.ready { background: #1683f8; }
    .otp-error { min-height: 22px; color: #e53935; margin-top: 12px; }
    .send-code-spinner { display: inline-block; width: 18px; height: 18px; border: 3px solid rgba(255,255,255,.45); border-top-color: #fff; border-radius: 50%; animation: send-code-spin .7s linear infinite; vertical-align: -3px; }
    .verify-otp-spinner { display: inline-block; width: 22px; height: 22px; border: 3px solid rgba(255,255,255,.45); border-top-color: #fff; border-radius: 50%; animation: send-code-spin .7s linear infinite; vertical-align: -4px; }
    @keyframes send-code-spin { to { transform: rotate(360deg); } }
    @media (max-width: 600px) { .otp-modal { padding: 42px 24px 40px; } .otp-boxes input { width: 58px; height: 62px; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('passwordRequestForm');
    const modal = document.getElementById('passwordOtpModal');
    const emailInput = form.querySelector('[name="email"]');
    const emailText = document.getElementById('otpEmail');
    const boxes = Array.from(document.querySelectorAll('#otpBoxes input'));
    const error = document.getElementById('otpError');
    const verify = document.getElementById('verifyOtp');

    function showModal(email) {
        emailText.textContent = email;
        error.textContent = '';
        boxes.forEach(box => box.value = '');
        modal.classList.remove('d-none');
        boxes[0].focus();
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const button = document.getElementById('sendVerificationCode');
        const label = button.querySelector('.send-code-label');
        const spinner = button.querySelector('.send-code-spinner');
        button.disabled = true;
        label.classList.add('d-none');
        spinner.classList.remove('d-none');
        fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(async response => { const data = await response.json(); if (!response.ok) throw new Error(data.message || 'Unable to send code.'); return data; })
            .then(data => showModal(data.email))
            .catch(exception => { error.textContent = exception.message; })
            .finally(() => {
                button.disabled = false;
                label.classList.remove('d-none');
                spinner.classList.add('d-none');
            });
    });

    boxes.forEach((box, index) => box.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '');
        if (this.value && boxes[index + 1]) boxes[index + 1].focus();
        verify.classList.toggle('ready', boxes.every(item => item.value.length === 1));
    }));

    verify.addEventListener('click', function () {
        const code = boxes.map(box => box.value).join('');
        if (code.length !== 4) return;
        const verifyLabel = verify.querySelector('.verify-otp-label');
        const verifySpinner = verify.querySelector('.verify-otp-spinner');
        verify.disabled = true;
        verifyLabel.classList.add('d-none');
        verifySpinner.classList.remove('d-none');
        fetch('{{ url('/verify-password-code') }}', { method: 'POST', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ email: emailText.textContent, code }) })
            .then(async response => { const data = await response.json(); if (!response.ok) throw new Error(data.message || 'Invalid code.'); return data; })
            .then(data => window.location.href = data.redirect)
            .catch(exception => {
                error.textContent = exception.message;
                verify.disabled = false;
                verifyLabel.classList.remove('d-none');
                verifySpinner.classList.add('d-none');
            });
    });

    document.getElementById('closeOtpModal').addEventListener('click', () => modal.classList.add('d-none'));
    document.getElementById('modifyOtpEmail').addEventListener('click', event => { event.preventDefault(); modal.classList.add('d-none'); emailInput.focus(); });
    document.getElementById('resendOtp').addEventListener('click', () => form.requestSubmit());
});
</script>
