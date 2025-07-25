<header class="bg-white border-b border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
        <a href="{{ url('/') }}" class="flex items-center gap-2 font-bold text-lg text-emerald-600">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                <circle cx="12" cy="12" r="10" />
            </svg>
            VigiaBR
        </a>
        <nav class="hidden md:flex gap-6 text-sm font-medium text-slate-600">
            <a href="{{ route('deputies.index') }}" class="hover:text-emerald-600">Buscar</a>
            <a href="{{ route('expenses.index') }}" class="hover:text-emerald-600">Gastos</a>
            <a href="{{ route('deputies.ranking') }}" class="hover:text-emerald-600">Ranking</a>
            <a href="{{ route('about') }}" class="hover:text-emerald-600">Sobre</a>
        </nav>
    </div>
</header>
