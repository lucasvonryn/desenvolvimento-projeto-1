<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Medições Wi-Fi') – {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4">
            <a href="{{ route('empresas.index') }}" class="text-lg font-semibold tracking-tight text-slate-900">
                Medições Wi-Fi
            </a>
            <nav class="flex items-center gap-3 text-sm">
                <a href="{{ route('empresas.index') }}" class="text-slate-600 hover:text-slate-900">Empresas</a>
                <a href="{{ route('empresas.create') }}" class="rounded-md bg-teal-700 px-3 py-1.5 font-medium text-white hover:bg-teal-800">Nova empresa</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8">
        @if (session('success'))
            <div class="mb-6 rounded-md border border-teal-200 bg-teal-50 px-4 py-3 text-sm text-teal-900">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
