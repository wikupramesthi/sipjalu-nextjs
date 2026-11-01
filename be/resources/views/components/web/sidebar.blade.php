<!-- Sidebar -->
<div id="sidebar">
    <div class="sidebar-wrapper active">

        {{-- Sidenav Header --}}
        <div class="sidenav-header">
            <a href="{{ route('dashboard.index') }}" class="navbar-brand m-0 d-flex align-items-center">
                <img id="logo-light" src="{{ asset('img/logo.png') }}" alt="Logo" class="navbar-brand-img">
                <img id="logo-dark" src="{{ asset('img/logo-white.png') }}" alt="Logo" class="navbar-brand-img d-none">
                <span class="sidebar-brand-copy">
                    <strong>Talent Hub</strong>
                    <small>Panel Administrasi</small>
                </span>
            </a>

            <div class="sidebar-toggler x">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>

        <hr class="horizontal dark mt-0">

        {{-- Menu --}}
        <div class="sidebar-menu">
            <ul class="menu navbar-nav">

                <li class="sidebar-title">Menu Utama</li>

                <li class="sidebar-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                    <a class="sidebar-link nav-link" href="{{ route('dashboard.index') }}">
                        <div class="icon icon-shape icon-sm">
                            <i class="bx bx-home"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>

                @foreach ($menus as $menu)
                @can($menu->permission_name)
                @php
                // Determine if any of the submenu items are active
                $isActive = false;
                foreach ($menu->items as $item) {
                if (request()->routeIs($item->route)) {
                $isActive = true;
                break;
                }
                }
                @endphp

                <li class="sidebar-item has-sub {{ $isActive ? 'active' : '' }}">
                    <a href="#" class="sidebar-link nav-link">
                        <div class="icon icon-shape icon-sm">
                            <i class="bx {{ $menu->icon }}"></i>
                        </div>
                        <span class="nav-link-text ms-1">{{ $menu->name }}</span>
                    </a>

                    <ul class="submenu nav ms-4">
                        @foreach ($menu->items as $item)
                        @can($item->permission_name)
                        @php($isCurrentPage = request()->routeIs($item->route))
                        <li class="submenu-item {{ $isCurrentPage ? 'active' : '' }}">
                            <a href="{{ route($item->route) }}" class="submenu-link nav-link"
                                @if ($isCurrentPage) aria-current="page" @endif>
                                <span class="sidenav-mini-icon">{{ strtoupper(mb_substr($item->name, 0, 1)) }}</span>
                                <span class="sidenav-normal">{{ $item->name }}</span>
                            </a>
                        </li>
                        @endcan
                        @endforeach
                    </ul>
                </li>

                <!-- end foreach items -->
                @endcan
                <!-- end can menu -->
                @endforeach

                @if (Auth::check())
                @can('admin')
                <li class="sidebar-item">
                    <a class="sidebar-link nav-link" href="#" target="_blank" title="User Guide">
                        <div class="icon icon-shape icon-sm">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <span class="nav-link-text ms-1">Panduan</span>
                    </a>
                </li>
                @endcan

                @role('user')
                <li class="sidebar-title">Menu Lain</li>
                <li class="sidebar-item has-sub">
                    <a href="#" class="sidebar-link nav-link">
                        <div class="icon icon-shape icon-sm">
                            <i class="bx bx-cog"></i>
                        </div>
                        <span class="nav-link-text ms-1">Settings</span>
                    </a>

                    <ul class="submenu nav ms-4">
                        <li class="submenu-item">
                            <a href="{{ route('profile.edit') }}" class="submenu-link nav-link">
                                <span class="sidenav-mini-icon">M</span>
                                <span class="sidenav-normal">My Profile</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole

                <li class="sidebar-item">
                    <a class="sidebar-link nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <div class="icon icon-shape icon-sm">
                            <i class="bi bi-arrow-bar-left"></i>
                        </div>
                        <span class="nav-link-text ms-1">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                @endif

            </ul>
        </div>

    </div>
</div>
