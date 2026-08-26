@extends("layouts.app")
@section("title", "Dashboard Demand")
@section("content")
<h1 class="text-2xl font-bold text-gray-900 mb-1">Halo, {{ auth()->user()->name }} 👋</h1>
<p class="text-gray-500 mb-6">Temukan material organik yang cocok untuk kebutuhan Anda.</p>

<div class="grid grid-cols-2 gap-4 mb-6">
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
        <p class="text-xs text-brand-100">diterima & dimanfaatkan</p>
    </div>
    @empty
    <div class="col-span-full bg-gray-50 rounded-2xl p-5 text-center text-sm text-gray-400">
        Belum ada transaksi selesai. Circular impact akan muncul di sini per satuan (kg, liter, karung, dst) setelah ada transaksi yang selesai.
    </div>
    @endforelse
</div>

<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold">Rekomendasi Untuk Anda</h2>
    <a href="{{ route("materials.index") }}" class="text-sm text-brand-700 font-medium">Cari lainnya →</a>
</div>
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @forelse($recommended as $m)
    <a href="{{ route("materials.show", $m) }}" class="bg-white rounded-2xl border border-gray-100 p-4 hover:shadow-md transition">
        <span class="text-xs px-2 py-0.5 rounded-full bg-brand-50 text-brand-700">{{ $m->category->name }}</span>
        <p class="font-semibold text-gray-900 mt-2">{{ $m->name }}</p>
        <p class="text-sm text-gray-500">{{ $m->quantity }} {{ $m->unit->symbol }} · {{ $m->location }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $m->supply->business_name ?? $m->supply->name }}</p>
    </a>
    @empty
    <p class="text-sm text-gray-400 col-span-full">Belum ada rekomendasi, coba jelajahi material yang tersedia.</p>
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
            <p class="text-xs text-gray-500">dari {{ $t->supply->business_name ?? $t->supply->name }}</p>
        </div>
        <span class="text-xs px-2.5 py-1 rounded-full bg-gray-100">{{ $t->statusLabel() }}</span>
    </a>
    @empty
    <p class="p-6 text-sm text-gray-400 text-center">Belum ada transaksi.</p>
    @endforelse
</div>
@endsection