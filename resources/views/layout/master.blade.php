<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {!! printHtmlAttributes('html') !!}>
<!--begin::Head-->

<head>
    <base href="" />
    <title>{{ config('app.name', 'CLOTHES STORE') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="" />
    <link rel="canonical" href="{{ url()->current() }}" />

    {!! includeFavicon() !!}

    <!--begin::Fonts-->
    {!! includeFonts() !!}
    <!--end::Fonts-->

    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    @foreach (getGlobalAssets('css') as $path)
        {!! sprintf('<link rel="stylesheet" href="%s">', asset($path)) !!}
    @endforeach
    <!--end::Global Stylesheets Bundle-->

    <!--begin::Vendor Stylesheets(used by this page)-->
    @foreach (getVendors('css') as $path)
        {!! sprintf('<link rel="stylesheet" href="%s">', asset($path)) !!}
    @endforeach
    <!--end::Vendor Stylesheets-->

    <!--begin::Custom Stylesheets(optional)-->
    @foreach (getCustomCss() as $path)
        {!! sprintf('<link rel="stylesheet" href="%s">', asset($path)) !!}
    @endforeach
    <!--end::Custom Stylesheets-->
    
    <!-- Custom validation fix CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom-validation-fix.css') }}">
    
    <!-- Custom sidebar fix CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom-sidebar-fix.css') }}">

    @livewireStyles
</head>
<!--end::Head-->

<!--begin::Body-->

<body {!! printHtmlClasses('body') !!} {!! printHtmlAttributes('body') !!}>

    @include('partials/theme-mode/_init')

    @yield('content')

    <!--begin::Javascript-->
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    @foreach (getGlobalAssets() as $path)
        {!! sprintf('<script src="%s"></script>', asset($path)) !!}
    @endforeach
    <!--end::Global Javascript Bundle-->

    <!--begin::Vendors Javascript(used by this page)-->
    @foreach (getVendors('js') as $path)
        {!! sprintf('<script src="%s"></script>', asset($path)) !!}
    @endforeach
    <!--end::Vendors Javascript-->

    <!--begin::Custom Javascript(optional)-->
    @foreach (getCustomJs() as $path)
        {!! sprintf('<script src="%s"></script>', asset($path)) !!}
    @endforeach
    <!--end::Custom Javascript-->
    <script src="{{ asset('assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>

    @stack('scripts')
    <!--end::Javascript-->
    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}");
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const maxSize = 300 * 1024; // 300 KB

            // Global function for file validation
            window.validateFile = function(input, errorContainerId) {
                const files = input.files;
                let errorMessage = '';

                for (let i = 0; i < files.length; i++) {
                    if (files[i].size > maxSize) {
                        errorMessage = `File "${files[i].name}" is too large. Maximum allowed size is 300 KB.`;
                        input.value = '';
                        break;
                    }
                }

                const errorContainer = document.getElementById(errorContainerId);
                if (errorContainer) {
                    errorContainer.textContent = errorMessage;
                }
            };
        });
    </script>


    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('success', (message) => {
                toastr.success(message);
            });
            Livewire.on('error', (message) => {
                toastr.error(message);
            });

            Livewire.on('swal', (message, icon, confirmButtonText) => {
                if (typeof icon === 'undefined') {
                    icon = 'success';
                }
                if (typeof confirmButtonText === 'undefined') {
                    confirmButtonText = 'Ok, got it!';
                }
                Swal.fire({
                    text: message,
                    icon: icon,
                    buttonsStyling: false,
                    confirmButtonText: confirmButtonText,
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            });
        });
    </script>

    @livewireScripts

    <!-- Fix sidebar dropdown behavior on mobile -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('kt_app_sidebar');
            if (!sidebar) return;

            // Get all menu items with dropdowns (menu triggers)
            const menuTriggers = sidebar.querySelectorAll('[data-kt-menu-trigger]');
            
            menuTriggers.forEach(trigger => {
                trigger.addEventListener('click', function(e) {
                    // Prevent drawer from closing when clicking dropdown triggers on mobile
                    e.stopPropagation();
                    
                    // Check if we're on mobile view
                    if (window.matchMedia('(max-width: 991px)').matches) {
                        // Prevent the default drawer close behavior
                        e.preventDefault = true;
                    }
                });
            });

            // Get all actual navigation links (not triggers)
            const navLinks = sidebar.querySelectorAll('.menu-item > a:not([data-kt-menu-trigger])');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Close sidebar only when clicking actual links, not triggers
                    if (window.matchMedia('(max-width: 991px)').matches) {
                        const sidebarToggle = document.getElementById('kt_app_sidebar_mobile_toggle');
                        if (sidebarToggle && sidebar.classList.contains('show')) {
                            setTimeout(() => {
                                sidebarToggle.click();
                            }, 200);
                        }
                    }
                });
            });

            // Prevent drawer from closing on menu trigger clicks
            // Intercept the drawer instance to modify close behavior
            const drawer = KTDrawer.getInstance(sidebar);
            if (drawer) {
                const originalHide = drawer.hide;
                drawer.hide = function() {
                    // Check if the last clicked element was a menu trigger
                    const lastClicked = document.activeElement;
                    if (lastClicked && lastClicked.closest('[data-kt-menu-trigger]')) {
                        // Don't close the drawer if a menu trigger was just clicked
                        return;
                    }
                    originalHide.call(this);
                };
            }
        });
    </script>
</body>
<!--end::Body-->

</html>
