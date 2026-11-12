{{-- Form input untuk data proyek (Bersih & Konsisten) --}}
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

    {{-- Kategori, Status Publikasi, & Tanggal Proyek --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
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

        <div>
            <label for="project_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Tanggal Proyek
            </label>
            <input type="date" name="project_date" id="project_date"
                value="{{ old('project_date', isset($project) && $project->project_date ? $project->project_date->format('Y-m-d') : '') }}"
                class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('project_date')
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

    {{-- Pilihan Teknologi (Checkbox Many-to-Many via project_technology) --}}
    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 bg-gray-50/60 dark:bg-gray-800/60 space-y-2">
        <div>
            <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Teknologi yang Digunakan
            </span>
            <p class="text-xs text-gray-500 dark:text-gray-400">Centang teknologi yang diterapkan pada proyek ini.</p>
        </div>

        @php
            $selectedTechIds = old('technologies', isset($project) ? $project->technologies->pluck('id')->toArray() : []);
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1">
            @foreach($technologies as $tech)
                <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700 cursor-pointer text-xs hover:border-blue-400 transition-colors">
                    <input type="checkbox" name="technologies[]" value="{{ $tech->id }}"
                        @checked(in_array($tech->id, $selectedTechIds))
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $tech->name }}</span>
                </label>
            @endforeach
        </div>
        @error('technologies')
            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Ringkasan Singkat (Short Description) --}}
    <div>
        <label for="short_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Ringkasan Singkat (Short Description)
        </label>
        <input type="text" name="short_description" id="short_description"
            value="{{ old('short_description', $project->short_description ?? '') }}"
            placeholder="Ringkasan 1-2 kalimat untuk ditampilkan di kartu proyek..."
            class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('short_description')
            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Deskripsi Proyek Lengkap --}}
    <div>
        <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Deskripsi Proyek Lengkap <span class="text-rose-500">*</span>
        </label>
        <textarea name="deskripsi" id="deskripsi" rows="4" required
            placeholder="Jelaskan gambaran umum, latar belakang, dan fitur utama proyek..."
            class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('deskripsi', $project->deskripsi ?? '') }}</textarea>
        @error('deskripsi')
            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tautan Live Demo & Repositori GitHub --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="tautan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Tautan Website / Live Demo
            </label>
            <input type="url" name="tautan" id="tautan" 
                value="{{ old('tautan', $project->tautan ?? '') }}"
                placeholder="https://contoh-demo.com"
                class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('tautan')
                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="repository_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Tautan Repositori GitHub
            </label>
            <input type="url" name="repository_url" id="repository_url" 
                value="{{ old('repository_url', $project->repository_url ?? '') }}"
                placeholder="https://github.com/username/repo"
                class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('repository_url')
                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
