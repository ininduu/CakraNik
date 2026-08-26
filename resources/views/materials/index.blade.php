@extends("layouts.app")
@section("title", "Cari Material")
@section("content")
<h1 class="text-2xl font-bold text-gray-900 mb-6">Cari Material Organik</h1>

<form method="GET" class="bg-white rounded-2xl border border-gray-100 p-4 mb-8 grid sm:grid-cols-4 gap-3">
    <input type="text" name="search" value="{{ request("search") }}" placeholder="Nama material..." class="rounded-lg border-gray-300 sm:col-span-2">
    <select name="category_id" class="rounded-lg border-gray-300">
        <option value="">Semua Kategori</option>
        @foreach($categories as $c)
            <option value="{{ $c->id }}" {{ request("category_id")==$c->id ? "selected" : "" }}>{{ $c->name }}</option>
        @endforeach
    </select>
    <input type="text" name="location" value="{{ request("location") }}" placeholder="Lokasi..." class="rounded-lg border-gray-300">
    <button class="sm:col-span-4 bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2 rounded-lg">Cari</button>
</form>

@if(auth()->user()->isDemand() && $recommended->isNotEmpty())
<div class="mb-8">
    <h2 class="text-lg font-semibold mb-3">Rekomendasi untuk Anda</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($recommended as $m)
        <a href="{{ route("materials.show", $m) }}" class="bg-brand-50 rounded-2xl border border-brand-100 overflow-hidden hover:shadow-md transition">
            <div class="aspect-square bg-brand-100 flex items-center justify-center overflow-hidden">
                @if($m->photo_path)
                    <img src="{{ asset('storage/' . $m->photo_path) }}" alt="{{ $m->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-4xl">🌱</span>
                @endif
            </div>
            <div class="p-4">
                <p class="font-semibold text-gray-900">{{ $m->name }}</p>
                <p class="text-sm text-gray-500">{{ $m->quantity }} {{ $m->unit->symbol }} · {{ $m->location }}</p>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($materials as $m)
    <a href="{{ route("materials.show", $m) }}" class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-md transition">
        <div class="aspect-video bg-gray-100 flex items-center justify-center overflow-hidden">
            @if($m->photo_path)
                <img src="{{ asset('storage/' . $m->photo_path) }}" alt="{{ $m->name }}" class="w-full h-full object-cover">
            @else
                <span class="text-5xl">🌱</span>
            @endif
        </div>
        <div class="p-4">
            <span class="text-xs px-2 py-0.5 rounded-full bg-brand-50 text-brand-700">{{ $m->category->name }}</span>
            <p class="font-semibold text-gray-900 mt-2">{{ $m->name }}</p>
            <p class="text-sm text-gray-500">{{ $m->quantity }} {{ $m->unit->symbol }} tersedia</p>
            <p class="text-xs text-gray-400 mt-1">📍 {{ $m->location }}</p>
            <p class="text-xs text-gray-400">{{ $m->supply->business_name ?? $m->supply->name }}</p>
        </div>
    </a>
    @empty
    <p class="text-gray-400 col-span-full text-center py-10">Tidak ada material ditemukan.</p>
    @endforelse
</div>

<div class="mt-6">{{ $materials->links() }}</div>
@endsection