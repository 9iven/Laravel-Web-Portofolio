{{-- partial form input untuk posts --}}
<div class="space-y-4">
    {{-- Judul Post --}}
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
            Judul Post <span class="text-rose-500">*</span>
        </label>
        <input type="text" name="title" id="title" 
            value="{{ old('title', $post->title ?? '') }}" required
            placeholder="Masukkan judul postingan..."
            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('title')
            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Deskripsi Post --}}
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
            Deskripsi Post <span class="text-rose-500">*</span>
        </label>
        <textarea name="description" id="description" rows="5" required
            placeholder="Tuliskan isi ringkasan atau detail deskripsi postingan..."
            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $post->description ?? '') }}</textarea>
        @error('description')
            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
