{{-- partial view for flashes --}}
@if (session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg" role="alert">
        {{ session('success') }}
    </div>
@elseif (session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-lg" role="alert">
        {{ session('error') }}
    </div>
@endif
