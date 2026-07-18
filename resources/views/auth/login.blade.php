<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login | IT Help Desk</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <main class="login-page">
        <section class="login-card">

            <div class="login-header">
                <h1>IT Help Desk</h1>
                <p>Sign in to manage and track support tickets.</p>
            </div>

            <form class="login-form" action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="Email"
                        placeholder="name@company.com"
                        value="{{ old('Email') }}"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="Password"
                        placeholder="Enter your password"
                    >
                </div>

                <div class="form-options">
                    <label>
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>

                    <a href="{{ route('passwordForgetpage') }}">Forgot password?</a>
                </div>
                              @error('Email')
    <div class="alert alert-danger" role="alert">
        {{ $message }}
</div>
@enderror
</div>
                              @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
</div>
@endif

                <button type="submit" class="login-button">
                    Sign In
                </button>


            </form>

        </section>
    </main>

</body>
</html>
