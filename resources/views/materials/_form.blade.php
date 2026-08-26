<div>
    <label class="block text-sm font-medium mb-1">Nama Material</label>
    <input name="name" value="{{ old("name", $material->name ?? "") }}" required class="w-full rounded-lg border-gray-300">
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Kategori</label>
        <select name="category_id" required class="w-full rounded-lg border-gray-300">
            @foreach($categories as $c)
                <option value="{{ $c->id }}" {{ old("category_id", $material->category_id ?? "")==$c->id ? "selected" : "" }}>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Satuan</label>
        <select name="unit_id" required class="w-full rounded-lg border-gray-300">
            @foreach($units as $u)
                <option value="{{ $u->id }}" {{ old("unit_id", $material->unit_id ?? "")==$u->id ? "selected" : "" }}>{{ $u->name }} ({{ $u->symbol }})</option>
            @endforeach
        </select>
    </div>
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Jumlah</label>
        <input type="number" step="0.01" name="quantity" value="{{ old("quantity", $material->quantity ?? "") }}" required class="w-full rounded-lg border-gray-300">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Status</label>
        <select name="status" class="w-full rounded-lg border-gray-300">
            <option value="available" {{ old("status", $material->status ?? "available")=="available" ? "selected" : "" }}>Tersedia</option>
            <option value="unavailable" {{ old("status", $material->status ?? "")=="unavailable" ? "selected" : "" }}>Tidak Tersedia</option>
        </select>
    </div>
</div>
<div>
    <label class="block text-sm font-medium mb-1">Lokasi</label>
    <input name="location" value="{{ old("location", $material->location ?? "") }}" required class="w-full rounded-lg border-gray-300">
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Tersedia Dari</label>
        <input type="date" name="available_from" value="{{ old("available_from", optional($material->available_from ?? null)->format("Y-m-d")) }}" class="w-full rounded-lg border-gray-300">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Tersedia Sampai</label>
        <input type="date" name="available_until" value="{{ old("available_until", optional($material->available_until ?? null)->format("Y-m-d")) }}" class="w-full rounded-lg border-gray-300">
    </div>
</div>
<div>
    <label class="block text-sm font-medium mb-1">Deskripsi</label>
    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300">{{ old("description", $material->description ?? "") }}</textarea>
</div>
<div>
    <label class="block text-sm font-medium mb-1">Foto Material</label>
    @if(isset($material) && $material?->photo_path)
    <p class="text-xs text-gray-400 mb-2">Foto saat ini (pilih file baru di bawah untuk menggantinya):</p>
    @endif

    <div class="flex items-center gap-3 mb-2">
        <img
            id="photo-preview"
            src="{{ isset($material) && $material?->photo_path ? asset('storage/' . $material->photo_path) : '' }}"
            alt="Preview foto"
            class="h-20 w-20 rounded-lg object-cover border border-gray-200 {{ isset($material) && $material?->photo_path ? '' : 'hidden' }}"
        >
        <span id="photo-preview-placeholder" class="text-xs text-gray-400 {{ isset($material) && $material?->photo_path ? 'hidden' : '' }}">Belum ada foto dipilih</span>
    </div>

    <input type="file" name="photo" accept="image/*" class="w-full text-sm"
        onchange="
            const file = this.files[0];
            const img = document.getElementById('photo-preview');
            const placeholder = document.getElementById('photo-preview-placeholder');
            if (file) {
                img.src = URL.createObjectURL(file);
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
        ">
</div>