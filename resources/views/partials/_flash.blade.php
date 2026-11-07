{{-- partial view for flashes --}}
@if (session('success'))
    <div class="mb-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 text-sm px-4 py-3 rounded-lg" role="alert">
        {{ session('success') }}
    </div>
@elseif (session('error'))
    <div class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 text-sm px-4 py-3 rounded-lg" role="alert">
        {{ session('error') }}
    </div>
@endif
