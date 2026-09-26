{{-- partial form input untuk projects --}}
<div class="space-y-4">
    {{-- Judul Proyek --}}
    <div>
        <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">
            Judul Proyek <span class="text-rose-500">*</span>
        </label>
        <input type="text" name="judul" id="judul" 
            value="{{ old('judul', $project->judul ?? '') }}" required
            placeholder="Contoh: Website Portofolio Mahasiswa"
            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('judul')
            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Kategori --}}
    <div>
        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">
            Kategori <span class="text-rose-500">*</span>
        </label>
        <input type="text" name="kategori" id="kategori" 
            value="{{ old('kategori', $project->kategori ?? '') }}" required
            placeholder="Contoh: Tugas Praktikum / Proyek Mandiri"
            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('kategori')
            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Deskripsi --}}
    <div>
        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
            Deskripsi Proyek <span class="text-rose-500">*</span>
        </label>
        <textarea name="deskripsi" id="deskripsi" rows="4" required
            placeholder="Jelaskan gambaran umum dan fitur utama proyek..."
            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('deskripsi', $project->deskripsi ?? '') }}</textarea>
        @error('deskripsi')
            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Teknologi --}}
    <div>
        <label for="teknologi" class="block text-sm font-medium text-gray-700 mb-1">
            Teknologi yang Digunakan <span class="text-rose-500">*</span>
        </label>
        <input type="text" name="teknologi" id="teknologi" 
            value="{{ old('teknologi', $project->teknologi ?? '') }}" required
            placeholder="Contoh: Laravel, Blade, Tailwind CSS, MySQL"
            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('teknologi')
            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tautan --}}
    <div>
        <label for="tautan" class="block text-sm font-medium text-gray-700 mb-1">
            Tautan GitHub / Repositori (Opsional)
        </label>
        <input type="url" name="tautan" id="tautan" 
            value="{{ old('tautan', $project->tautan ?? '') }}"
            placeholder="https://github.com/username/repository"
            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('tautan')
            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
