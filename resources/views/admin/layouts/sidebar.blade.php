<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}">Lombok Gunung</a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="LOGO Cabai Gunung" srcset="Logo Cabai Gunung" style="max-width: 40px;">
        </a>
      </div>
    <ul class="sidebar-menu">
        <li class="{{ Route::current()->getName() == 'dashboard' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i> <span>Dashboard</span>
            </a>
        </li>
        <li class="{{ Route::current()->getName() == 'chillis.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('chillis.index') }}">
                <i class="fas fa-archive"></i> <span>Jenis Cabai</span>
            </a>
        </li>
        <li class="{{ Route::current()->getName() == 'chilli-price.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('chilli-price.index') }}">
                <i class="fas fa-pepper-hot"></i> <span>Harga Cabai</span>
            </a>
        </li>
        <li class="{{ Route::current()->getName() == 'purchases.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('purchases.index') }}">
                <i class="fas fa-shopping-cart"></i> <span>Pembelian</span>
            </a>
        </li>
        <li class="{{ Route::current()->getName() == 'sales.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('sales.index') }}">
                <i class="fas fa-shopping-bag"></i> <span>Penjualan</span>
            </a>
        </li>
        <li class="{{ Route::current()->getName() == 'users.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="fas fa-user"></i> <span>Pengguna</span>
            </a>
        </li>
    </ul>
</aside>
