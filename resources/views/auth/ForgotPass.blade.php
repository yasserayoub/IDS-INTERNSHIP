<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | IT Help Desk</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<main class="login-page">

    <section class="login-card">

        <div class="login-header">
            <h1>Forgot Password</h1>
            <p>Enter your email to receive a password reset link.</p>
        </div>

        @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif

@error('Email')
    <div class="alert alert-danger" role="alert">
        {{ $message }}
    </div>
@enderror

        <form action="{{ route('SendResetLink') }}" method="POST">

            @csrf

            <div class="form-group">

                <label>Email Address</label>

                <input
                    type="email"
                    name="Email"
                    placeholder="name@company.com"
                    value="{{ old('Email') }}"
                    required>

            </div>

            <button class="login-button" type="submit">
                Send Reset Link
            </button>

        </form>

    </section>

</main>

</body>
</html>
