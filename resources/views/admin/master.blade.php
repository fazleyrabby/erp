<!DOCTYPE html>
<html lang="en">

@include('admin.includes.header')

<body class="layout-fluid">
    <div class="page">

        @include('admin.includes.sidebar')

        <div class="page-wrapper">

            @include('admin.includes.topbar')
            <div class="page-body">
                <div class="container-xl">
                    @yield('content')
                </div>
            </div>
        </div>

    </div>

    @include('admin.includes.js')
    @yield('javascript')
    @yield('contentJavaScripts')
    @stack('javascript')
</body>

</html>
