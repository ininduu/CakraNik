@extends("layouts.app")
@section("title", "Profil")
@section("content")
<div class="max-w-lg mx-auto bg-white rounded-2xl border border-gray-100 p-6">
    <h1 class="text-xl font-bold text-gray-900 mb-6">Kelola Profil</h1>
    <form method="POST" action="{{ route("profile.update") }}" class="space-y-4">
        @csrf @method("PATCH")
        <div>
            <label class="block text-sm font-medium mb-1">Nama</label>
            <input name="name" value="{{ old("name", $user->name) }}" required class="w-full rounded-lg border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old("email", $user->email) }}" required class="w-full rounded-lg border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Nama Usaha</label>
            <input name="business_name" value="{{ old("business_name", $user->business_name) }}" class="w-full rounded-lg border-gray-300">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">No. Telepon</label>
                <input name="phone" value="{{ old("phone", $user->phone) }}" class="w-full rounded-lg border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Kota</label>
                <input name="city" value="{{ old("city", $user->city) }}" class="w-full rounded-lg border-gray-300">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Alamat</label>
            <textarea name="address" rows="2" class="w-full rounded-lg border-gray-300">{{ old("address", $user->address) }}</textarea>
        </div>
        <p class="text-xs text-gray-400">Jenis akun: {{ $user->isSupply() ? "Supply" : "Demand" }}</p>
        <button class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 rounded-lg">Simpan Perubahan</button>
    </form>
</div>
@endsection