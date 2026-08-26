@extends("layouts.app")
@section("title", $material->name)
@section("content")
<div class="max-w-2xl mx-auto bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="aspect-video bg-gray-100 flex items-center justify-center overflow-hidden">
        @if($material->photo_path)
            <img src="{{ asset('storage/' . $material->photo_path) }}" alt="{{ $material->name }}" class="w-full h-full object-cover">
        @else
            <span class="text-6xl">🌱</span>
        @endif
    </div>

    <div class="p-6">
        <span class="text-xs px-2 py-0.5 rounded-full bg-brand-50 text-brand-700">{{ $material->category->name }}</span>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $material->name }}</h1>
        <p class="text-gray-500 text-sm mb-4">Ditawarkan oleh {{ $material->supply->business_name ?? $material->supply->name }} · 📍 {{ $material->location }}</p>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-500">Jumlah Tersedia</p>
                <p class="text-xl font-bold">{{ $material->quantity }} {{ $material->unit->symbol }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-500">Status</p>
                <p class="text-xl font-bold {{ $material->status=="available" ? "text-brand-600" : "text-gray-400" }}">
                    {{ $material->status=="available" ? "Tersedia" : "Tidak Tersedia" }}
                </p>
            </div>
        </div>

        @if($material->description)
        <p class="text-gray-700 mb-4">{{ $material->description }}</p>
        @endif

        @if($material->available_from || $material->available_until)
        <p class="text-sm text-gray-500 mb-6">Ketersediaan: {{ optional($material->available_from)->format("d M Y") ?? "-" }} s/d {{ optional($material->available_until)->format("d M Y") ?? "-" }}</p>
        @endif

        @if(auth()->user()->isDemand() && $material->status == "available")
        <form method="POST" action="{{ route("transactions.store", $material) }}" class="border-t pt-4 space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Jumlah yang diminta ({{ $material->unit->symbol }})</label>
                <input type="number" step="0.01" max="{{ $material->quantity }}" min="0.01" name="requested_quantity" required class="w-full rounded-lg border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Catatan (opsional)</label>
                <textarea name="note" rows="2" class="w-full rounded-lg border-gray-300"></textarea>
            </div>
            <button class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 rounded-lg">Ajukan Permintaan</button>
        </form>
        @endif
    </div>
</div>
@endsection