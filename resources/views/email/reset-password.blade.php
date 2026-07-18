<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>
<body>

<h2>IT Help Desk</h2>

<p>Hello,</p>

<p>We received a request to reset your password.</p>

<p>
    Click the link below to create a new password:
</p>

<p>
    <a href="{{ url('/passwordresetform/'.$token) }}">
        Reset Password
    </a>
</p>

<p>
    If you did not request a password reset, you can safely ignore this email.
</p>

</body>
</html>
