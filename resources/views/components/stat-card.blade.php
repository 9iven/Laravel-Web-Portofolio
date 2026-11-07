@props(['label', 'value', 'color' => 'text-blue-600'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-5 flex flex-col justify-between">
    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold">{{ $label }}</div>
    <div class="text-2xl font-bold {{ $color }} mt-2">{{ $value }}</div>
</div>
