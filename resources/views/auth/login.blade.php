<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

            <form class="login-form">

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        placeholder="name@company.com"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        placeholder="Enter your password"
                    >
                </div>

                <div class="form-options">
                    <label>
                        <input type="checkbox">
                        Remember me
                    </label>

                    <a href="#">Forgot password?</a>
                </div>

                <button type="button" class="login-button">
                    Sign In
                </button>

            </form>

        </section>
    </main>

</body>
</html>
