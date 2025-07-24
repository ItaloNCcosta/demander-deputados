<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \$title }}</title>
    <meta name="color-scheme" content="light dark">
    <style>[x-cloak] { display: none !important; }</style>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-emerald-200/60">
    <x-guest-header />

    <main class="py-6">
        {{ \$slot }}
    </main>

    <footer class="bg-slate-900 text-slate-300 text-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-4 text-center">
        <p>Fonte: API Oficial da Câmara dos Deputados. Dados atualizados periodicamente.</p>
        <p>&copy; {{ date('Y') }} VigilaBR — Projeto aberto. <a href="#" class="underline hover:text-white">GitHub</a></p>
      </div>
    </footer>

    @stack('scripts')
</body>
</html>