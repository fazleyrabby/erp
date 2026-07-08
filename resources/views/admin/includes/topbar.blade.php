<header class="navbar navbar-expand-md d-print-none" data-bs-theme="dark">
    <div class="container-fluid">
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-brand navbar-brand-autodark d-lg-none">
            <a href="{{route('dashboard')}}">
                <span style="font-size:1.1rem; font-weight:600;">{{Session::get('companySettings')[0]['name']}}</span>
            </a>
        </div>

        <div class="navbar-nav flex-row ms-auto">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url({{!empty(Auth::user()->image) ? url('upload/user_images/'.Auth::user()->image) : url('upload/no-image.jpg')}})"></span>
                    <div class="d-none d-xl-block ps-2">
                        <div>{{Auth::user()->name}}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<div id="loading" style="display:none;">
    <div class="lds-ripple"><div class="lds-pos"></div><div class="lds-pos"></div></div>
</div>
