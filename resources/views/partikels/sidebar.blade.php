<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if (auth()->user()->photo)
                    <img src="{{ asset('photos/' . auth()->user()->photo) }}" class="img-circle elevation-2"
                        alt="User Image">
                @else
                    <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                        alt="Default Image">
                @endif
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <!-- Menu untuk Admin -->
                @if (auth()->user()->role == 'admin')
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!-- Data Jabatan -->
                    <li class="nav-item">
                        <a href="{{ route('data-jabatan.index') }}"
                            class="nav-link {{ request()->is('data-jabatan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-briefcase"></i>
                            <p>Data Jabatan</p>
                        </a>
                    </li>

                    <!-- Data Karyawan -->
                    <li class="nav-item">
                        <a href="{{ route('karyawan.index') }}"
                            class="nav-link {{ request()->is('karyawan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Data Karyawan</p>
                        </a>
                    </li>

                    <!-- Setting Absensi / Jam Kerja -->
                    <li class="nav-item">
                        <a href="{{ route('absensi-settings.index') }}"
                            class="nav-link {{ request()->is('absensi-settings*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clock"></i>
                            <p>Setting Absensi</p>
                        </a>
                    </li>

                    <!-- Setting Hari Libur -->
                    <li class="nav-item">
                        <a href="{{ route('holidays.index') }}"
                            class="nav-link {{ request()->is('holidays*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Setting Hari Libur</p>
                        </a>
                    </li>

                    <!-- Setting Denda Keterlambatan -->
                    <li class="nav-item">
                        <a href="{{ route('penalties.index') }}"
                            class="nav-link {{ request()->is('penalties*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>Setting Denda</p>
                        </a>
                    </li>

                    <!-- Data Absensi -->
                    <li class="nav-item">
                        <a href="{{ route('absensi.index') }}"
                            class="nav-link {{ request()->is('data-absensi*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Data Absensi</p>
                        </a>
                    </li>

                    <!-- Penggajian -->
                    <li class="nav-item">
                        <a href="{{ route('penggajian.index') }}"
                            class="nav-link {{ request()->is('penggajian*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-check-alt"></i>
                            <p>Penggajian</p>
                        </a>
                    </li>
                @endif

                <!-- Menu untuk Karyawan -->
                @if (auth()->user()->role == 'karyawan')
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!-- Data Absensi Karyawan -->
                    <li class="nav-item">
                        <a href="{{ route('absensi.index') }}"
                            class="nav-link {{ request()->is('data-absensi*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Data Absensi</p>
                        </a>
                    </li>

                    <!-- Penggajian -->
                    <li class="nav-item">
                        <a href="{{ route('penggajian.employee') }}"
                            class="nav-link {{ request()->is('penggajian/employee') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-check-alt"></i>
                            <p>Penggajian</p>
                        </a>
                    </li>
                @endif

                <!-- Profil -->
                <li class="nav-item">
                    <a href="{{ route('account') }}" class="nav-link {{ request()->is('account*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profil</p>
                    </a>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
