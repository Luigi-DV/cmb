<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--Favicon-->
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon.ico')}}">
    <title>{{ config('app.name', 'CMB') }}</title>
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <livewire:styles />
</head>
<body class="font-sans antialiased ">
    <div class="bg-gray-100 dark:bg-black">
        <x-navigation.navigation-bar />
        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="py-6 px-4 sm:px-6 lg:px-8 text-gray-700 dark:text-gray-200">
                    {{ $header }}
                </div>
            </header>
        @endif
        <!--Page Content-->
        <main>
            {{ $slot }}
        </main>
    </div>
    <x-navigation.footer/>
    @stack('modals')
    <livewire:scripts />
</body>
</html>
