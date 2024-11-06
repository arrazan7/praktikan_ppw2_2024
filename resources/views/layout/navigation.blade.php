<nav class="navbar navbar-expand-lg bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ URL('/') }}">Laravel 11</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('login') ? 'active' : '' }}"
                            href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('register') ? 'active' : '' }}"
                            href="{{ route('register') }}">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('send-email') ? 'active' : '' }}"
                            href="{{ route('send.email') }}">Send Email</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('gallery') ? 'active' : '' }}"
                            href="{{ route('gallery.index') }}">Book Gallery</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex flex-row align-items-center" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            @if (Auth::user()->photo)
                                <img src="{{ asset('storage/images/users/thumbnail/' . Auth::user()->photo . '_Thumbnail.' . Auth::user()->photo_ext) }}"
                                    alt="Avatar" class="rounded-circle border border-3 border-primary me-2" width="35"
                                    height="35">
                            @else
                                <img src="{{ asset('storage/images/users/thumbnail/default.png') }}" alt="Avatar"
                                    class="rounded-circle border border-3 border-primary me-2" width="35"
                                    height="35">
                            @endif
                            <span class="me-1">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('users.edit', Auth::user()->id) }}">Edit
                                    Profile</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('users.index') }}">User List</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
