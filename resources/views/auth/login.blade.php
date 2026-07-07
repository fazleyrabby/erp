<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{Session::get('companySettings')[0]['name'] ?? 'ERP'}} Login</title>
    <link href="{{asset('tabler/css/tabler.min.css')}}" rel="stylesheet" />
    <link href="{{asset('backend/dist/css/icons/font-awesome/css/fontawesome-all.min.css')}}" rel="stylesheet">
    <style>
        @import url("https://rsms.me/inter/inter.css");
    </style>
</head>
<body class="border-top-wide border-primary">
    <script src="{{asset('tabler/js/tabler-theme.min.js')}}"></script>
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="#" class="navbar-brand navbar-brand-autodark">
                    <h3 class="mb-0">{{Session::get('companySettings')[0]['name'] ?? 'ERP'}}</h3>
                </a>
            </div>

            <div class="card card-md">
                <div class="card-body">
                    <h2 class="h2 text-center mb-4">Login to your account</h2>
                    <form method="POST" action="{{url('login')}}" autocomplete="off" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="your@email.com" autocomplete="off" autofocus />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Password</label>
                            <div class="input-group input-group-flat">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Your password" autocomplete="off" />
                                <span class="input-group-text">
                                    <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip" onclick="togglePassword()">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($errors->any())
                        <div class="alert alert-danger mt-3">
                            @foreach($errors->all() as $error)
                            <strong>{{$error}}</strong>
                            @endforeach
                        </div>
                        @endif

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100">Sign in</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card card-md mt-3">
                <div class="card-body">
                    <h3 class="h3 text-center mb-3">Demo Login</h3>
                    <p class="text-secondary text-center mb-3">Click a role to login instantly</p>
                    <div class="row g-2">
                        <div class="col">
                            <a href="#" class="btn btn-2 w-100" onclick="demoLogin('super.admin@gmail.com', '12345678')">
                                <i class="fa fa-user-shield me-2"></i>Super Admin
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="btn btn-2 w-100" onclick="demoLogin('manager@demo.com', 'demo1234')">
                                <i class="fa fa-user-tie me-2"></i>Manager
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="btn btn-2 w-100" onclick="demoLogin('salesman@demo.com', 'demo1234')">
                                <i class="fa fa-user me-2"></i>Sales Man
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('tabler/js/tabler.min.js')}}" defer></script>
    <script src="{{asset('backend/assets/libs/jquery/dist/jquery.min.js')}}"></script>
    <script>
        function togglePassword() {
            var input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
        function demoLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            document.querySelector('form').submit();
        }
    </script>
</body>
</html>
