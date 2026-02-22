<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMRS</title>
    @vite(['resources/css/login.css','resources/js/login.js'])
</head>
<body>

<div class="container">
    <div class="left">
        <div class="title">Sistem Informasi <b>Rumah Sakit</b></div>
        <div class="subtitle">Belum ada Rumah Sakitnya</div>
        <img src="{{ asset('images/logo/hello.png') }}">
    </div>

    <div class="right">
        <div class="login-card">
            <h2>Halo</h2>
            <p>Selamat Datang!</p>

            <form id="form-login">
                @csrf
                <input type="text" name="username" id="username" placeholder="Username">
                <input type="password" name="password" id="password" placeholder="Password">
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
