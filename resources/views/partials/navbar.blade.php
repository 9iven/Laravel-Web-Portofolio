<nav class="border-b p-4">
    <div class="container mx-auto max-w-2xl flex justify-between items-center">
        <a href="{{ route('home') }}" class="font-bold text-base">Portofolio Website Given</a>
        <div class="flex gap-4 text-sm">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'font-bold text-blue-600' : 'text-gray-600' }}">Home</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'font-bold text-blue-600' : 'text-gray-600' }}">About</a>
            <a href="{{ route('education') }}" class="{{ request()->routeIs('education') ? 'font-bold text-blue-600' : 'text-gray-600' }}">Education</a>
            <a href="{{ route('projects') }}" class="{{ request()->routeIs('projects') ? 'font-bold text-blue-600' : 'text-gray-600' }}">Projects</a>
        </div>
    </div>
</nav>
