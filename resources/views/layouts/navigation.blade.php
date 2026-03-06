<nav class="navbar navbar-expand-lg navbar-lu py-3">
    <div class="container">
        @php $user = auth()->user(); @endphp
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ $user && $user->isInstructor() ? route('instructor.dashboard') : ($user && $user->isAdmin() ? route('admin.dashboard') : route('courses.index')) }}" style="color: var(--lu-deep-purple);">
            <img src="/images/life-university-logo.png" alt="LU Academy" height="36" class="me-2">
            LU Academy
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth
                    @if(Auth::user()->isInstructor())
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('instructor.*') ? 'active' : '' }}" href="{{ route('instructor.dashboard') }}">My Courses</a></li>
                    @elseif(Auth::user()->isAdmin())
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Admin</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('courses.*') && !request()->routeIs('instructor.*') ? 'active' : '' }}" href="{{ route('courses.index') }}">Courses</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('discussions.*') ? 'active' : '' }}" href="{{ route('discussions.index') }}">Community</a></li>
                    @endif
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('courses.index') }}">Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('discussions.index') }}">Community</a></li>
                @endauth
            </ul>
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ Auth::user()->name }}</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                            @if(Auth::user()->isInstructor())
                                <li><a class="dropdown-item" href="{{ route('instructor.dashboard') }}">Manage courses</a></li>
                            @endif
                            @if(Auth::user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin dashboard</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">@csrf
                                    <button type="submit" class="dropdown-item">Log Out</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Log in</a></li>
                    <li class="nav-item"><a class="btn btn-lu-primary btn-sm ms-2" href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
