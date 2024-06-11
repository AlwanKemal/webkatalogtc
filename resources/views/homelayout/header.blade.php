<header class="navbar navbar-light sticky-top bg-light flex-md-nowrap p-0">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 text-center" href="#">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" width="150px">
    </a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-nav">
      <div class="nav-item text-nowrap d-flex align-items-center">
        <img src="{{ asset('images/Avatar.png') }}" alt="avatar" width="32px" class="rounded-circle me-2">
        <div class="order-1 d-flex align-items-center">
          <span class="nav-link px-3">{{ Auth::user()->fullname }}</span>
          <a href="#" class="nav-link px-3 text-danger d-flex align-items-center" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt me-1"></i>
            Logout
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </div>
    </div>
</header>
