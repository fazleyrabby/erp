<header class="navbar navbar-expand-md d-none d-lg-flex d-print-none" data-bs-theme="dark">
    <div class="container-fluid">


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
