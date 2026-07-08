<div class="menu-item pt-5">
    <div class="menu-content">
        <span class="menu-heading fw-bold text-uppercase fs-7">Website</span>
    </div>
</div>
<div class="menu menu-column menu-rounded menu-sub-indention px-3 fw-semibold fs-6" id="#kt_app_sidebar_menu"
    data-kt-menu="true" data-kt-menu-expand="false">
    <!--begin:Menu item-->
    <div data-kt-menu-trigger="click"
        class="menu-item menu-accordion {{ request()->routeIs('dashboard') ? 'here show' : '' }}">
        <!--begin:Menu link-->
        <span class="menu-link">
            <span class="menu-icon">{!! getIcon('element-11', 'fs-2') !!}</span>
            <span class="menu-title">Dashboards</span>
            <span class="menu-arrow"></span>
        </span>
        <!--end:Menu link-->

        <div class="menu-sub menu-sub-accordion">
            <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('admin-landing-page.*') ? 'active' : '' }}"
                    href="{{ route('admin-landing-page.list') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">{{ __('Landing Page') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
