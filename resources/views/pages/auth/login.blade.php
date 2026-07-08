<x-auth-layout>

    <!--begin::Form-->
    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="{{ route('dashboard') }}"
        action="{{ route('login') }}">
        @csrf
        <!--begin::Heading-->
        <div class="text-center mb-11">
            <!--begin::Title-->
            <h1 class="text-gray-900 fw-bolder mb-3">
                Sign In
            </h1>
            <!--end::Title-->

            {{-- <div class="text-gray-500 fw-semibold fs-6">
                Your Social Campaigns
            </div> --}}
        </div>

        {{-- <div class="row g-3 mb-9">
            <div class="col-md-6">
                <a href="{{ url('/auth/redirect/google') }}?redirect_uri={{ url()->current() }}"
                    class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                    <img alt="Logo" src="{{ image('svg/brand-logos/google-icon.svg') }}" class="h-15px me-3" />
                    Sign in with Google
                </a>
            </div>

            <div class="col-md-6">
                <a href="#"
                    class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                    <img alt="Logo" src="{{ image('svg/brand-logos/apple-black.svg') }}"
                        class="theme-light-show h-15px me-3" />
                    <img alt="Logo" src="{{ image('svg/brand-logos/apple-black-dark.svg') }}"
                        class="theme-dark-show h-15px me-3" />
                    Sign in with Apple
                </a>
            </div>
        </div>

        <div class="separator separator-content my-14">
            <span class="w-125px text-gray-500 fw-semibold fs-7">Or with email</span>
        </div> --}}

        <!--begin::Input group--->
        <div class="fv-row mb-8 position-relative">
            <!--begin::Email-->
            <input type="text" placeholder="Email" name="email" autocomplete="off"
                class="form-control bg-transparent" value="" />
            <!--end::Email-->
        </div>

        <!--end::Input group--->
        <div class="fv-row mb-3 position-relative">
            <!--begin::Password-->
            <input type="password" placeholder="Password" name="password" autocomplete="off"
                class="form-control bg-transparent" value="" id="login_password" />
            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" 
                onclick="togglePasswordVisibility('login_password', this)" style="cursor: pointer; z-index: 10;">
                <i class="bi bi-eye-slash fs-2"></i>
                <i class="bi bi-eye fs-2 d-none"></i>
            </span>
            <!--end::Password-->
        </div>
        <!--end::Input group--->

        <!--begin::Wrapper-->
        <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
            <div></div>

            <!--begin::Link-->
            <a href="{{ route('password.request') }}" class="link-primary">
                Forgot Password ?
            </a>
            <!--end::Link-->
        </div>
        <!--end::Wrapper-->

        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                @include('partials/general/_button-indicator', ['label' => 'Sign In'])
            </button>
        </div>
        <!--end::Submit button-->

        <!--begin::Sign up-->
        <div class="text-gray-500 text-center fw-semibold fs-6">
            Not a Member yet?

            <a href="{{ route('register') }}" class="link-primary">
                Sign up
            </a>
        </div>
        <!--end::Sign up-->
    </form>
    <!--end::Form-->

    @push('scripts')
    <script>
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const eyeSlash = button.querySelector('.bi-eye-slash');
            const eye = button.querySelector('.bi-eye');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeSlash.classList.add('d-none');
                eye.classList.remove('d-none');
            } else {
                input.type = 'password';
                eyeSlash.classList.remove('d-none');
                eye.classList.add('d-none');
            }
        }
        
        // Remove duplicate validation error messages
        document.addEventListener('DOMContentLoaded', function() {
            // Use MutationObserver to watch for dynamically added error messages
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length) {
                        removeDuplicateErrors();
                    }
                });
            });
            
            // Start observing the form
            const form = document.querySelector('#kt_sign_in_form');
            if (form) {
                observer.observe(form, {
                    childList: true,
                    subtree: true
                });
            }
            
            function removeDuplicateErrors() {
                const errorContainers = document.querySelectorAll('.fv-row');
                errorContainers.forEach(function(container) {
                    const messages = container.querySelectorAll('.fv-plugins-message-container');
                    if (messages.length > 1) {
                        // Keep only the first error message, remove the rest
                        for (let i = 1; i < messages.length; i++) {
                            messages[i].remove();
                        }
                    }
                });
            }
        });
    </script>
    @endpush

</x-auth-layout>
