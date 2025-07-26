<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ "VigiaBR - {$title}" ?? 'VigiaBR' }}</title>
    <meta name="color-scheme" content="light dark">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @stack('styles')
</head>

<body class="flex flex-col min-h-screen bg-slate-50 text-slate-800 antialiased selection:bg-emerald-200/60">
    <x-header-layout />

    <main class="flex-grow">
        {{ $slot }}
    </main>

    <footer class="bg-slate-900 text-slate-300 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-4 text-center">
            <p>Fonte: API Oficial da Câmara dos Deputados. Dados atualizados periodicamente.</p>
            <p>&copy; {{ date('Y') }} VigiaBR — Projeto aberto. <a href="#"
                    class="underline hover:text-white">GitHub</a></p>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
