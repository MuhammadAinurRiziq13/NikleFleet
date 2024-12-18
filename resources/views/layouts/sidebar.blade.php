<ul class="navbar-nav bg-dark-blue sidebar sidebar-dark accordion " id="accordionSidebar">

    <!-- Sidebar - Brand -->

    {{-- <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#"> --}}
    {{-- <div class="sidebar-brand-icon rotate-n-15"> --}}
    {{-- <i class="fas fa-user-cog"></i> --}}
    <a href="#" class="sidebar-brand d-flex align-items-center justify-content-center">
        <div class="sidebar-brand-text mx-2 ">NikleFleet</div>
    </a>
    {{-- </div> --}}
    {{-- </a> --}}

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a href="{{ url('/dashboard') }}" class="nav-link">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Reservasi
    </div>

    <li
        class="nav-item {{ request()->is('reservation*') || request()->is('submission-reservation*') ? 'active' : '' }} ">
        <a href="{{ url('/reservation') }}" class="nav-link">
            <!-- Vehicle reservation icon -->
            <i class="fas fa-fw fa-car-side"></i>
            <span>Reservasi Kendaraan</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Semua Data
    </div>

    <li class="nav-item {{ request()->is('vehicle*') ? 'active' : '' }}">
        <a href="{{ url('/vehicle') }}" class="nav-link">
            <!-- Vehicle data icon -->
            <i class="fas fa-fw fa-car"></i>
            <span>Data Kendaraan</span>
        </a>
    </li>

    <li class="nav-item {{ request()->is('employee*') ? 'active' : '' }}">
        <a href="{{ url('/employee') }}" class="nav-link">
            <!-- Employee data icon -->
            <i class="fas fa-fw fa-users"></i>
            <span>Data Pegawai</span>
        </a>
    </li>

    <li class="nav-item {{ request()->is('mine*') ? 'active' : '' }}">
        <a href="{{ url('/mine') }}" class="nav-link">
            <!-- Mining/industry icon -->
            <i class="fas fa-fw fa-industry"></i>
            <span>Data Tambang</span>
        </a>
    </li>

    <li class="nav-item {{ request()->is('region*') ? 'active' : '' }}">
        <a href="{{ url('/region') }}" class="nav-link">
            <!-- Mining/industry icon -->
            <i class="fas fa-fw fa-city"></i>
            <span>Data Region</span>
        </a>
    </li>
    <hr class="sidebar-divider">

</ul>
