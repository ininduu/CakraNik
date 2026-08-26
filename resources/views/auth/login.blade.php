@extends("layouts.app")
@section("title", "Masuk")
@section("content")
<div class="max-w-md mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Masuk ke CakraNik</h1>
    <p class="text-sm text-gray-500 mb-6">Perputaran bahan organik, dari Supply ke Demand.</p>

    <form method="POST" action="{{ route("login") }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old("email") }}" required autofocus class="w-full rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Kata Sandi</label>
            <input type="password" name="password" required class="w-full rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500">
        </div>
        <label class="flex items-center gap-2 text-sm text-gray-600">
            <input type="checkbox" name="remember"> Ingat saya
        </label>
        <button class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 rounded-lg">Masuk</button>
    </form>

    <p class="text-sm text-gray-500 mt-4 text-center">Belum punya akun? <a href="{{ route("register") }}" class="text-brand-700 font-medium">Daftar</a></p>

    <div class="mt-6 border-t pt-4 text-xs text-gray-400">
        Demo: supply@cakranik.test / demand@cakranik.test — password: <code>password</code>
    </div>
</div>
@endsection