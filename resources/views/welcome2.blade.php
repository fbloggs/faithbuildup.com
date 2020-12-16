<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">


    <style>
        body {
            font-family: 'Nunito';
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="fixed flex items-start justify-between  bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
    <div class="contents">    
    <!-- Logo -->
        <div class="flex-1 px-6 py-4 sm:block">
            <img src="/images/wcflogo.png" class="h-4 w-full" style="height: auto; width: auto; max-height: 80px; max-width: 100%;" />
        </div>
        <div class="flex-1">
            @if (Route::has('login'))
            <div class="px-6 py-4 sm:block">
                @auth
                <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Login</a>

                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                @endif
                @endauth
            </div>
            @endif
        </div>
    </div> 
    </div>
    <div> 
        <p>Content below the navbar goes here</p>
    </div>
</body>

</html>