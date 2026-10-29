<nav class="border-b p-4">
    <div class="container mx-auto max-w-2xl flex justify-between items-center">
        <a href="{{ route('home') }}" class="font-bold text-base">Portofolio Website Given</a>
        <div class="flex gap-4 text-sm">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'font-bold text-blue-600' : 'text-gray-600' }}">Home</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'font-bold text-blue-600' : 'text-gray-600' }}">About</a>
            <a href="{{ route('education') }}" class="{{ request()->routeIs('education') ? 'font-bold text-blue-600' : 'text-gray-600' }}">Education</a>
            <a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.*') ? 'font-bold text-blue-600' : 'text-gray-600' }}">Projects</a>
            <a href="{{ route('posts.index') }}" class="{{ request()->routeIs('posts.*') ? 'font-bold text-blue-600' : 'text-gray-600' }}">Posts</a>

            @auth
                <span class="text-xs text-gray-500 font-semibold">({{ auth()->user()->name }})</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-xs text-rose-600 font-medium hover:underline">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700 font-medium">Login</a>
            @endauth
        </div>
    </div>
</nav>
