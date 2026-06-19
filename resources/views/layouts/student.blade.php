<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sistem Pendataan PKL' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen font-sans antialiased relative overflow-x-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 inset-x-0 h-96 bg-gradient-to-b from-violet-100/40 to-transparent dark:from-violet-900/20 -z-10"></div>
    <div class="absolute top-0 inset-x-0 flex justify-center -z-10 overflow-hidden pointer-events-none">
        <div class="w-full flex-none flex justify-center">
            <div class="w-[80rem] flex-none bg-gradient-to-r from-violet-400/20 to-fuchsia-400/20 dark:from-violet-600/10 dark:to-fuchsia-600/10 blur-[100px] h-[40rem] rounded-full translate-y-[-20%]"></div>
        </div>
    </div>

    <div class="w-full relative z-0">
        {{ $slot }}
    </div>
    @livewireScripts
</body>
</html>
