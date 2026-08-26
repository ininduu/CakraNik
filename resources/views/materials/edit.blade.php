@extends("layouts.app")
@section("title", "Ubah Material")
@section("content")
<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-100 p-6">
    <h1 class="text-xl font-bold text-gray-900 mb-6">Ubah Material</h1>
    <form method="POST" action="{{ route("materials.update", $material) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf @method("PUT")
        @include("materials._form")
        <button class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 rounded-lg">Perbarui</button>
    </form>
</div>
@endsection