@extends("layouts.app")
@section("title", "Material Saya")
@section("content")
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Material Saya</h1>
    <a href="{{ route("materials.create") }}" class="bg-brand-600 hover:bg-brand-700 text-white font-semibold px-4 py-2 rounded-lg text-sm">+ Tambah Material</a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 divide-y">
    @forelse($materials as $m)
    <div class="flex items-center justify-between p-4 gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <div class="h-14 w-14 rounded-lg bg-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden">
                @if($m->photo_path)
                    <img src="{{ asset('storage/' . $m->photo_path) }}" alt="{{ $m->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-xl">🌱</span>
                @endif
            </div>
            <div class="min-w-0">
                <p class="font-medium text-gray-900 truncate">{{ $m->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $m->category->name }} · {{ $m->quantity }} {{ $m->unit->symbol }} · {{ $m->location }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <span class="text-xs px-2.5 py-1 rounded-full {{ $m->status=="available" ? "bg-brand-50 text-brand-700" : "bg-gray-100 text-gray-500" }}">
                {{ $m->status=="available" ? "Tersedia" : "Tidak Tersedia" }}
            </span>
            <a href="{{ route("materials.edit", $m) }}" class="text-sm text-brand-700 font-medium">Ubah</a>
            <form method="POST" action="{{ route("materials.destroy", $m) }}" onsubmit="return confirm(&quot;Hapus material ini?&quot;)">
                @csrf @method("DELETE")
                <button class="text-sm text-red-600 font-medium">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <p class="p-6 text-sm text-gray-400 text-center">Belum ada material yang Anda tambahkan.</p>
    @endforelse
</div>
<div class="mt-6">{{ $materials->links() }}</div>
@endsection