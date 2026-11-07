<nav x-data="{ isDark: document.documentElement.classList.contains('dark') }" class="border-b p-4 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 transition-colors duration-200">
    <div class="container mx-auto max-w-5xl flex justify-between items-center">
        <a href="{{ route('home') }}" class="font-bold text-base text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Portofolio Website Given</a>
        
        <div class="flex gap-4 text-sm items-center">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }} transition-colors">Home</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }} transition-colors">About</a>
            <a href="{{ route('education') }}" class="{{ request()->routeIs('education') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }} transition-colors">Education</a>
            <a href="{{ route('projects') }}" class="{{ request()->routeIs('projects*') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }} transition-colors">Projects</a>
            <a href="{{ route('posts.index') }}" class="{{ request()->routeIs('posts.*') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }} transition-colors">Posts</a>

            <!-- Theme Toggle Button (Dark Mode / Light Mode) -->
            <button type="button"
                @click="
                    isDark = !isDark;
                    if (isDark) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    }
                "
                class="p-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors cursor-pointer inline-flex items-center justify-center"
                :title="isDark ? 'Ganti ke Mode Terang (Light Mode)' : 'Ganti ke Mode Gelap (Dark Mode)'"
                aria-label="Toggle dark mode">
                <!-- Sun Icon (Tampil saat Dark Mode aktif) -->
                <svg x-show="isDark" class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" style="display: none;">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                </svg>
                <!-- Moon Icon (Tampil saat Light Mode aktif) -->
                <svg x-show="!isDark" class="w-4 h-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
            </button>

            @auth
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }} transition-colors">Dashboard</a>
                <span class="text-xs text-gray-500 dark:text-gray-400">({{ auth()->user()->name }})</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-rose-600 dark:text-rose-400 font-medium hover:underline cursor-pointer">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700 font-medium transition-colors">Login</a>
            @endauth
        </div>
    </div>
</nav>