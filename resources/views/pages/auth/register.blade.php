<x-auth-layout>

    <!--begin::Form-->
    <form class="form w-100" method="POST" novalidate="novalidate" id="kt_sign_up_form" data-kt-redirect-url="{{ route('login') }}"
        action="{{ route('register') }}">
        @csrf
        <input type="hidden" name="_iframe" value="1">
        <!--begin::Heading-->
        <div class="text-center mb-11">
            <!--begin::Title-->
            <h1 class="text-gray-900 fw-bolder mb-3" style="font-size: 28px; color: #1a1a1a;">
                Sign Up
            </h1>
            <!--end::Title-->

            {{-- <div class="text-gray-500 fw-semibold fs-6">
                Your Social Campaigns
            </div> --}}
        </div>
        <!--begin::Heading-->

        <!--begin::Login options-->
        {{-- <div class="row g-3 mb-9">
            <div class="col-md-6">
                <a href="#"
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
        </div> --}}
        <!--end::Login options-->

        {{-- <div class="separator separator-content my-14">
            <span class="w-125px text-gray-500 fw-semibold fs-7">Or with email</span>
        </div> --}}

        <!--begin::Input group--->
        <div class="fv-row mb-8">
            <!--begin::Name-->
            <input type="text" placeholder="Name" name="name" autocomplete="off"
                class="form-control" style="padding: 12px 16px; border: 1px solid #d4d4d4; border-radius: 4px; font-size: 14px;" />
            <!--end::Name-->
        </div>

        <!--begin::Input group--->
        <div class="fv-row mb-8">
            <!--begin::Email-->
            <input type="text" placeholder="Email" name="email" autocomplete="off"
                class="form-control" style="padding: 12px 16px; border: 1px solid #d4d4d4; border-radius: 4px; font-size: 14px;" />
            <!--end::Email-->
        </div>

        <!--begin::Input group-->
        <div class="fv-row mb-8" data-kt-password-meter="true">
            <!--begin::Wrapper-->
            <div class="mb-1">
                <!--begin::Input wrapper-->
                <div class="position-relative mb-3">
                    <input class="form-control" type="password" placeholder="Password" name="password"
                        autocomplete="off" style="padding: 12px 16px; border: 1px solid #d4d4d4; border-radius: 4px; font-size: 14px;" />

                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                        data-kt-password-meter-control="visibility" style="background: none; border: none; color: #999;">
                        <i class="bi bi-eye-slash fs-2"></i>
                        <i class="bi bi-eye fs-2 d-none"></i>
                    </span>
                </div>
                <!--end::Input wrapper-->

                <!--begin::Meter-->
                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                </div>
                <!--end::Meter-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Hint-->
            <div class="text-muted" style="font-size: 12px; color: #666;">
                Use 8 or more characters with a mix of letters, numbers & symbols.
            </div>
            <!--end::Hint-->
        </div>
        <!--end::Input group--->

        <!--end::Input group--->
        <div class="fv-row mb-8">
            <!--begin::Repeat Password-->
            <input placeholder="Repeat Password" name="password_confirmation" type="password" autocomplete="off"
                class="form-control" style="padding: 12px 16px; border: 1px solid #d4d4d4; border-radius: 4px; font-size: 14px;" />
            <!--end::Repeat Password-->
        </div>
        <!--end::Input group--->

        <!--begin::Input group--->
        <div class="fv-row mb-10">
            <div class="form-check form-check-custom form-check-solid form-check-inline">
                <input class="form-check-input" type="checkbox" name="toc" value="1" />

                <label class="form-check-label fw-semibold text-gray-700 fs-6" style="font-size: 14px;">
                    I Agree &

                    <a href="#" class="ms-1 link-primary" style="color: #d4af37;">Terms and conditions</a>.
                </label>
            </div>
        </div>
        <!--end::Input group--->

        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_up_submit" class="btn btn-primary" style="padding: 12px 24px; background-color: #d4af37; color: #1a1a1a; border: none; border-radius: 4px; font-weight: 600; font-size: 14px; cursor: pointer;">
                @include('partials/general/_button-indicator', ['label' => 'Sign Up'])
            </button>
        </div>
        <!--end::Submit button-->

        <!--begin::Sign up-->
        <div class="text-gray-500 text-center fw-semibold fs-6" style="font-size: 14px; color: #666;">
            Already have an Account?

            <a href="/login" class="link-primary fw-semibold" style="color: #d4af37;">
                Sign in
            </a>
        </div>
        <!--end::Sign up-->
    </form>
    <!--end::Form-->

</x-auth-layout>

<style>
    body {
        background-color: #fff;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    #kt_sign_up_form {
        width: min(430px, calc(100vw - 48px)) !important;
        max-width: none !important;
        margin-inline: auto !important;
    }

    #kt_sign_up_form .fv-row,
    #kt_sign_up_form .form-control,
    #kt_sign_up_form #kt_sign_up_submit {
        width: 100% !important;
        max-width: none !important;
        box-sizing: border-box;
    }

    #kt_sign_up_form .form-control {
        color: #333;
        background-color: #fff;
        border-color: #d4d4d4;
    }

    #kt_sign_up_form .form-control:focus {
        color: #333;
        background-color: #fff;
        border-color: #d4af37;
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
    }

    #kt_sign_up_form .form-control::placeholder {
        color: #999;
        opacity: 1;
    }
</style>
