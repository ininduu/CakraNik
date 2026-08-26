@extends("layouts.app")
@section("title", "Daftar")
@section("content")
<div class="max-w-lg mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Buat Akun CakraNik</h1>
    <p class="text-sm text-gray-500 mb-6">Bergabung sebagai penyalur atau penerima material organik.</p>

    <form method="POST" action="{{ route("register") }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-2">Saya adalah</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="border rounded-xl p-3 cursor-pointer has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50">
                    <input type="radio" name="role" value="supply" class="mr-2" {{ old("role")=="supply" ? "checked" : "" }} required>
                    <span class="font-medium">Supply</span>
                    <p class="text-xs text-gray-500 mt-1">Rumah makan, hotel, katering</p>
                </label>
                <label class="border rounded-xl p-3 cursor-pointer has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50">
                    <input type="radio" name="role" value="demand" class="mr-2" {{ old("role")=="demand" ? "checked" : "" }}>
                    <span class="font-medium">Demand</span>
                    <p class="text-xs text-gray-500 mt-1">Peternak, petani, budidaya maggot</p>
                </label>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
            <input name="name" value="{{ old("name") }}" required class="w-full rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Nama Usaha (opsional)</label>
            <input name="business_name" value="{{ old("business_name") }}" class="w-full rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">No. Telepon</label>
                <input name="phone" value="{{ old("phone") }}" class="w-full rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Kota</label>
                <input name="city" value="{{ old("city") }}" class="w-full rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old("email") }}" required class="w-full rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Kata Sandi</label>
                <input type="password" name="password" required class="w-full rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Ulangi Kata Sandi</label>
                <input type="password" name="password_confirmation" required class="w-full rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500">
            </div>
        </div>

        <button class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 rounded-lg">Daftar</button>
    </form>

    <p class="text-sm text-gray-500 mt-4 text-center">Sudah punya akun? <a href="{{ route("login") }}" class="text-brand-700 font-medium">Masuk</a></p>
</div>
@endsection