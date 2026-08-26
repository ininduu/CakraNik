@extends("layouts.app")
@section("title", "Tambah Material")
@section("content")
<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-100 p-6">
    <h1 class="text-xl font-bold text-gray-900 mb-6">Tambah Material Organik</h1>
    <form method="POST" action="{{ route("materials.store") }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @include("materials._form", ["material" => null])
        <button class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 rounded-lg">Simpan</button>
    </form>
</div>

@endsection