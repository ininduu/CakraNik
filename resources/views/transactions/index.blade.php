@extends("layouts.app")
@section("title", "Riwayat Transaksi")
@section("content")
<h1 class="text-2xl font-bold text-gray-900 mb-6">Riwayat Transaksi</h1>

<form method="GET" class="bg-white rounded-2xl border border-gray-100 p-4 mb-4 space-y-3">
    @if(request("status"))
        <input type="hidden" name="status" value="{{ request("status") }}">
    @endif

    <div class="flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Dari Tanggal</label>
            <input type="date" name="date_from" value="{{ request("date_from") }}" class="rounded-lg border-gray-300 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
            <input type="date" name="date_to" value="{{ request("date_to") }}" class="rounded-lg border-gray-300 text-sm">
        </div>
        <button class="bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">Terapkan</button>
        <a href="{{ route("transactions.index", ["date_filter" => "today"] + (request("status") ? ["status" => request("status")] : [])) }}"
           class="text-sm font-medium px-4 py-2 rounded-lg {{ request("date_filter")=="today" ? "bg-brand-600 text-white" : "bg-gray-100 text-gray-600" }}">
            Hari Ini
        </a>
        @if(request("date_from") || request("date_to") || request("date_filter"))
        <a href="{{ route("transactions.index", request("status") ? ["status" => request("status")] : []) }}" class="text-sm text-gray-400 underline">Reset filter tanggal</a>
        @endif
    </div>
</form>

<div class="flex gap-2 mb-6 flex-wrap">
    <a href="{{ route("transactions.index") }}" class="px-3 py-1.5 rounded-full text-xs font-medium {{ !request("status") ? "bg-brand-600 text-white" : "bg-gray-100" }}">Semua</a>
    @foreach(\App\Models\Transaction::statusLabels() as $key => $label)
        <a href="{{ route("transactions.index", ["status" => $key]) }}" class="px-3 py-1.5 rounded-full text-xs font-medium {{ request("status")==$key ? "bg-brand-600 text-white" : "bg-gray-100" }}">{{ $label }}</a>
    @endforeach
</div>

<div class="bg-white rounded-2xl border border-gray-100 divide-y">
    @forelse($transactions as $t)
    <a href="{{ route("transactions.show", $t) }}" class="flex items-center justify-between p-4 hover:bg-gray-50">
        <div>
            <p class="font-medium text-gray-900">{{ $t->material->name }}</p>
            <p class="text-xs text-gray-500">
                {{ auth()->user()->isSupply() ? "Demand: " . $t->demand->name : "Supply: " . ($t->supply->business_name ?? $t->supply->name) }}
                · {{ $t->requested_quantity }} {{ $t->material->unit->symbol }} · {{ $t->created_at->format("d M Y, H:i") }}
            </p>
        </div>
        <span class="text-xs px-2.5 py-1 rounded-full bg-gray-100">{{ $t->statusLabel() }}</span>
    </a>
    @empty
    <p class="p-6 text-sm text-gray-400 text-center">Tidak ada transaksi pada rentang/filter ini.</p>
    @endforelse
</div>
<div class="mt-6">{{ $transactions->links() }}</div>
@endsection