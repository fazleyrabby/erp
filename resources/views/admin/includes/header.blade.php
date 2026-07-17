<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('backend/assets/images/favicon.png')}}">
    <title>@yield('title')</title>

    <!-- Tabler Theme (must be in head) -->
    <script src="{{asset('tabler/js/tabler-theme.min.js')}}"></script>

    <!-- Tabler CSS -->
    <link href="{{asset('tabler/css/tabler.min.css')}}" rel="stylesheet">
    <link href="{{asset('tabler/css/tabler-vendors.min.css')}}" rel="stylesheet">

    <!-- Font Awesome (kept from old theme) -->
    <link href="{{asset('backend/dist/css/icons/font-awesome/css/fontawesome-all.min.css')}}" rel="stylesheet">

    <!-- jQuery (required by existing app scripts) -->
    <script src="{{asset('backend/assets/libs/jquery/dist/jquery.min.js')}}"></script>

    <!-- DataTables -->
    <link href="{{asset('backend/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">

    <!-- Toastr -->
    <link href="{{asset('backend/dist/css/toastr.css')}}" rel="stylesheet">

    <!-- Select2 -->
    <link href="{{asset('backend/dist/css/select2.min.css')}}" rel="stylesheet">

    <!-- Custom Styles -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/tabler-polish.css')}}" rel="stylesheet">

    <style>
        #loading {
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            position: fixed;
            display: block;
            opacity: 0.7;
            background-color: #fff;
            z-index: 99;
            text-align: center;
        }
        #loading-image {
            position: absolute;
            top: 48%;
            left: 48%;
            z-index: 100;
        }
        .lds-ripple {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }
        .lds-ripple .lds-pos {
            position: absolute;
            border: 4px solid #7460ee;
            opacity: 1;
            border-radius: 50%;
            animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
        }
        .lds-ripple .lds-pos:nth-child(2) {
            animation-delay: -0.5s;
        }
        @keyframes lds-ripple {
            0% { top: 36px; left: 36px; width: 0; height: 0; opacity: 0; }
            4.9% { top: 36px; left: 36px; width: 0; height: 0; opacity: 0; }
            5% { top: 36px; left: 36px; width: 0; height: 0; opacity: 1; }
            100% { top: 0px; left: 0px; width: 72px; height: 72px; opacity: 0; }
        }
    </style>

    <!-- Bootstrap 5 jQuery compatibility bridge -->
    <script>
        (function($) {
            var bridgeReady = false;
            var initBridge = function() {
                if (typeof bootstrap === 'undefined') return false;
                $.fn.modal = function(action) {
                    if (action === 'show') {
                        return this.each(function() {
                            var modal = bootstrap.Modal.getOrCreateInstance(this);
                            modal.show();
                        });
                    }
                    if (action === 'hide') {
                        return this.each(function() {
                            var modal = bootstrap.Modal.getInstance(this);
                            if (modal) modal.hide();
                        });
                    }
                    if (action === 'toggle') {
                        return this.each(function() {
                            var modal = bootstrap.Modal.getInstance(this);
                            if (modal) modal.toggle();
                        });
                    }
                };
                bridgeReady = true;
                return true;
            };
            if (!initBridge()) {
                var checkInterval = setInterval(function() {
                    if (initBridge()) clearInterval(checkInterval);
                }, 50);
            }
        })(jQuery);
    </script>
</head>
