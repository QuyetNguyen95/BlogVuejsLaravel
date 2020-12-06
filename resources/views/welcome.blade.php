<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">
</head>
<body class="antialiased">
    <div id="app">
        @if(\Illuminate\Support\Facades\Auth::check())
            <app :user="{{\Illuminate\Support\Facades\Auth::user()}}" :permission="{{\Illuminate\Support\Facades\Auth::user()->role->permission}}"></app>
        @else
            <app :user="false"></app>
        @endif
    </div>
</body>
<script type="text/javascript">
    window.csrf_token = "{{ csrf_token() }}"
</script>
<script src="{{ mix('js/app.js') }}"></script>
</html>
