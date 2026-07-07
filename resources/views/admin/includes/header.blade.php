<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('backend/assets/images/favicon.png')}}">
    <title>@yield('title')</title>
    <!-- Custom CSS -->
    <link href="{{asset('backend/assets/libs/flot/css/float-chart.css')}}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{asset('backend/dist/css/style.min.css')}}" rel="stylesheet">

     <!--sweetLalert-->
<!--     <link rel="stylesheet" href="sweetalert2.min.css">
 -->
    <!--dataTables-->
    <link href="{{asset('backend/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
	<!--<link rel="stylesheet" href="https://jafree.alitechbd.com/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="https://jafree.alitechbd.com/css/buttons.dataTables.min.css"/>-->
    <!--Toaster
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">-->
    <link href="{{asset('backend/dist/css/toastr.css')}}" rel="stylesheet">
    <!-- for jquery validation jquery.min.js file have to use with css file that mean uppper side---->
    <!---Jquery---->
      <script src="{{asset('backend/assets/libs/jquery/dist/jquery.min.js')}}"></script>
      <meta name="csrf-token" content="{{ csrf_token() }}" />
	  <link href="{{asset('css/style.css')}}" rel="stylesheet">
      <!--link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /-->
      <link href="{{asset('backend/dist/css/select2.min.css')}}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{asset('public/backend/')}}https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="{{asset('public/backend/')}}https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->



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
</style>
</head>