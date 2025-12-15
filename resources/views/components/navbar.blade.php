<nav class="navbar">
  <div class="container-fluid mx-0 px-5">
      <form class="d-none d-md-flex input-group w-50 my-auto" role="search">
        <input type="text" id="search" class="form-control" placeholder="Search menu items..." oninput="filterMenuItems()">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
      @auth
      <ul class="navbar-nav ms-auto me-3 d-flex flex-row">
        <li class="nav-item dropdown position-relative">
          <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong> {{ Auth::user()->name; }} </strong>
          </a>
          <ul class="dropdown-menu dropdown-menu-end position-absolute">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li>
              <form action="{{ route('logout' )}}" method="post" class="m-0">
                @csrf
                <button class="dropdown-item" type="submit">Sign out</button>
              </form>
            </li>
          </ul>
        </li>
      </ul>
      @endauth
      @guest
      <ul class="navbar-nav ms-auto me-3 d-flex flex-row align-items-center">
        <li class="nav-item me-3">
          <a href="{{ route('register') }}" class="nav-link">Register</a>
        </li>
      
        <li class="nav-item">
          <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4">Login</a>
        </li>
      </ul>
      @endguest
  </div>
</nav>