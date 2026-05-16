<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ScreenWise') - Tracking System</title>
    <meta name="description" content="ScreenWise Tracking System - Membantu siswa membangun kebiasaan digital sehat">
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
</head>
<body>
    @yield('content')
</body>
</html>
