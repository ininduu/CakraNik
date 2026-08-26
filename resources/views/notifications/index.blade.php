@extends("layouts.app")
@section("title", "Notifikasi")
@section("content")
<h1 class="text-2xl font-bold text-gray-900 mb-6">Notifikasi</h1>
<div class="bg-white rounded-2xl border border-gray-100 divide-y max-w-xl">
    @forelse($notifications as $n)
    <a href="{{ route("transactions.show", $n->data["transaction_id"]) }}" class="block p-4 hover:bg-gray-50">
        <p class="font-medium text-gray-900 text-sm">{{ $n->data["title"] }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $n->data["message"] }}</p>
        <p class="text-xs text-gray-300 mt-1">{{ $n->created_at->diffForHumans() }}</p>
    </a>
    @empty
    <p class="p-6 text-sm text-gray-400 text-center">Belum ada notifikasi.</p>
    @endforelse
</div>
<div class="mt-6 max-w-xl">{{ $notifications->links() }}</div>
@endsection