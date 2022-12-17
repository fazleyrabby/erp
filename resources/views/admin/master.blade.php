<!DOCTYPE html>
<html dir="ltr" lang="en">

@include('admin.includes.header')

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->

        @include('admin.includes.topbar')

        @include('admin.includes.sidebar')

        <div class="page-wrapper">

            <div class="container-fluid">

                @yield('content')

            </div>

        </div>

    </div>


    @include('admin.includes.js')

    @yield('javascript')
    @yield('contentJavaScripts')
</body>

</html>
