<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | IT Help Desk</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<main class="login-page">

    <section class="login-card">

        <div class="login-header">
            <h1>Reset Password</h1>
            <p>Enter your new password below.</p>
        </div>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error-message">
                <ul style="margin:0; padding-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('passwordUpdate') }}" method="POST">

            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label>New Password</label>

                <input
                    type="password"
                    name="Password"
                    placeholder="Enter your new password"
                    required>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>

                <input
                    type="password"
                    name="Password_confirmation"
                    placeholder="Confirm your new password"
                    required>
            </div>

            <button class="login-button" type="submit">
                Reset Password
            </button>

        </form>

    </section>

</main>

</body>
</html>
