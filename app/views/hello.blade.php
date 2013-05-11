<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Auth</title>
</head>
<style>
    .message {
        width: 100%;
        padding: 10px 0;
        text-align: center;
        background-color: whitesmoke;
    }
</style>
<body>

@if (Session::has('message') )
    <div class="message">
        {{ Session::get('message') }}
    </div>
@endif

<h1>Laravel 4 basic Auth</h1>
<ul>
    <li><a href="auth/login">Login</a></li>
    <li><a href="auth/register">Register</a></li>
    <li><a href="auth/forgot">Forgot password</a></li>
    <li><a href="auth/logout">Logout</a></li>
</ul>

</body>
</html>