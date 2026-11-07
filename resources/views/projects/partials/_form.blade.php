{{-- partial form input untuk projects --}}
<div class="space-y-4">
    {{-- Judul Proyek --}}
    <div>
        <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Judul Proyek <span class="text-rose-500">*</span>
        </label>
        <input type="text" name="judul" id="judul" 
            value="{{ old('judul', $project->judul ?? '') }}" required
            placeholder="Contoh: Website Portofolio Mahasiswa"
            class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('judul')
            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Kategori & Status Publikasi dalam Grid 2 Kolom --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Kategori <span class="text-rose-500">*</span>
            </label>
            <select name="category_id" id="category_id" required
                class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $project->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Status Publikasi <span class="text-rose-500">*</span>
            </label>
            <select name="status" id="status" required
                class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="published" {{ old('status', $project->status ?? 'published') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ old('status', $project->status ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
            @error('status')
                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Area Thumbnail Proyek --}}
    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 bg-gray-50/60 dark:bg-gray-800/60 space-y-3">
        <div>
            <label for="thumbnail" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Thumbnail Proyek
            </label>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Format JPG, JPEG, PNG, WebP. Min. 400x300 px, maks. 2MB.</p>
        </div>

        @if(isset($project) && $project->thumbnail)
            <div class="flex items-center gap-3 p-2.5 bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="Thumbnail saat ini" class="w-16 h-12 rounded object-cover border border-gray-200 dark:border-gray-600 shadow-xs">
                <div class="text-xs text-gray-600 dark:text-gray-300">
                    <span class="font-medium text-gray-800 dark:text-gray-200 block">Thumbnail saat ini</span>
                    <span>Pilih berkas baru di bawah jika ingin mengganti thumbnail.</span>
                </div>
            </div>
        @endif

        <div>
            <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                class="block w-full text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 focus:outline-none p-1.5">
            @error('thumbnail')
                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Teknologi & Tautan Repositori/Demo dalam Grid 2 Kolom --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="teknologi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Teknologi yang Digunakan <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="teknologi" id="teknologi" 
                value="{{ old('teknologi', $project->teknologi ?? '') }}" required
                placeholder="Contoh: Laravel, Tailwind CSS, SQLite"
                class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('teknologi')
                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="tautan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Tautan Repositori / Demo
            </label>
            <input type="url" name="tautan" id="tautan" 
                value="{{ old('tautan', $project->tautan ?? '') }}"
                placeholder="https://github.com/username/project"
                class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('tautan')
                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Deskripsi Proyek --}}
    <div>
        <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Deskripsi Proyek <span class="text-rose-500">*</span>
        </label>
        <textarea name="deskripsi" id="deskripsi" rows="4" required
            placeholder="Jelaskan gambaran umum dan fitur utama proyek..."
            class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('deskripsi', $project->deskripsi ?? '') }}</textarea>
        @error('deskripsi')
            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
