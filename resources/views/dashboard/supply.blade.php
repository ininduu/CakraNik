@extends("layouts.app")
@section("title", "Dashboard Supply")
@section("content")
<h1 class="text-2xl font-bold text-gray-900 mb-1">Halo, {{ auth()->user()->name }} 👋</h1>
<p class="text-gray-500 mb-6">Ringkasan aktivitas penyaluran material organik Anda.</p>

<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <p class="text-xs text-gray-500">Material Aktif</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats["active_materials"] }}</p>
        <p class="text-xs text-gray-400">dari {{ $stats["total_materials"] }} total listing</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <p class="text-xs text-gray-500">Permintaan Menunggu</p>
        <p class="text-2xl font-bold text-amber-600">{{ $stats["pending_requests"] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <p class="text-xs text-gray-500">Transaksi Selesai</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats["completed_transactions"] }}</p>
    </div>
</div>

<h2 class="text-lg font-semibold mb-3">♻ Circular Impact <span class="text-xs font-normal text-gray-400">(dipisah per satuan)</span></h2>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @forelse($impactByUnit as $impact)
    <div class="bg-brand-600 rounded-2xl p-5 text-white">
        <p class="text-xs text-brand-100">{{ $impact->unit_name }}</p>
        <p class="text-2xl font-bold">{{ number_format($impact->total, 1) }} {{ $impact->unit_symbol }}</p>
        <p class="text-xs text-brand-100">berhasil disalurkan</p>
    </div>
    @empty
    <div class="col-span-full bg-gray-50 rounded-2xl p-5 text-center text-sm text-gray-400">
        Belum ada transaksi selesai. Circular impact akan muncul di sini per satuan (kg, liter, karung, dst) setelah ada transaksi yang selesai.
    </div>
    @endforelse
</div>

<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold">Transaksi Terbaru</h2>
    <a href="{{ route("transactions.index") }}" class="text-sm text-brand-700 font-medium">Lihat semua →</a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 divide-y">
    @forelse($recentTransactions as $t)
    <a href="{{ route("transactions.show", $t) }}" class="flex items-center justify-between p-4 hover:bg-gray-50">
        <div>
            <p class="font-medium text-gray-900">{{ $t->material->name }}</p>
            <p class="text-xs text-gray-500">dari {{ $t->demand->name }} · {{ $t->requested_quantity }} {{ $t->material->unit->symbol }}</p>
        </div>
        <span class="text-xs px-2.5 py-1 rounded-full bg-gray-100">{{ $t->statusLabel() }}</span>
    </a>
    @empty
    <p class="p-6 text-sm text-gray-400 text-center">Belum ada transaksi.</p>
    @endforelse
</div>

<div class="mt-6">
    <a href="{{ route("materials.create") }}" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-5 py-2.5 rounded-lg">
        + Tambah Material
    </a>
</div>
@endsection