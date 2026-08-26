<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield("title", "CakraNik") — Perputaran Bahan Organik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { brand: { 50:"#f0fdf4",100:"#dcfce7",500:"#22c55e",600:"#16a34a",700:"#15803d" } } } }
        }
    </script>
    @stack("head")
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

<nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <a href="{{ route("dashboard") }}" class="flex items-center gap-2 font-bold text-xl text-brand-700">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-brand-500 text-white">♻</span>
                CakraNik
            </a>

            @auth
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route("dashboard") }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs("dashboard") ? "bg-brand-50 text-brand-700" : "text-gray-600 hover:bg-gray-100" }}">Dashboard</a>
                <a href="{{ route("materials.index") }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs("materials.index") || request()->routeIs("materials.show") ? "bg-brand-50 text-brand-700" : "text-gray-600 hover:bg-gray-100" }}">Cari Material</a>
                @if(auth()->user()->isSupply())
                <a href="{{ route("materials.my") }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs("materials.my") || request()->routeIs("materials.create") || request()->routeIs("materials.edit") ? "bg-brand-50 text-brand-700" : "text-gray-600 hover:bg-gray-100" }}">Material Saya</a>
                @endif
                <a href="{{ route("transactions.index") }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs("transactions.*") ? "bg-brand-50 text-brand-700" : "text-gray-600 hover:bg-gray-100" }}">Transaksi</a>
                <a href="{{ route("notifications.index") }}" class="relative px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">
                    Notifikasi
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                        <span class="absolute top-1 right-0 h-2 w-2 rounded-full bg-red-500"></span>
                    @endif
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route("profile.edit") }}" class="text-sm text-gray-600 hover:text-brand-700 hidden sm:inline">{{ auth()->user()->name }}</a>
                <span class="hidden sm:inline text-xs px-2 py-1 rounded-full {{ auth()->user()->isSupply() ? "bg-blue-100 text-blue-700" : "bg-amber-100 text-amber-700" }}">
                    {{ auth()->user()->isSupply() ? "Supply" : "Demand" }}
                </span>
                <form method="POST" action="{{ route("logout") }}">
                    @csrf
                    <button class="text-sm font-medium text-gray-500 hover:text-red-600">Keluar</button>
                </form>
            </div>
            @endauth
        </div>
    </div>
    @auth
    <div class="md:hidden flex overflow-x-auto gap-1 px-4 pb-3">
        <a href="{{ route("dashboard") }}" class="whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100">Dashboard</a>
        <a href="{{ route("materials.index") }}" class="whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100">Cari Material</a>
        @if(auth()->user()->isSupply())
        <a href="{{ route("materials.my") }}" class="whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100">Material Saya</a>
        @endif
        <a href="{{ route("transactions.index") }}" class="whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100">Transaksi</a>
        <a href="{{ route("notifications.index") }}" class="whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100">Notifikasi</a>
    </div>
    @endauth
</nav>

<main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(session("success"))
        <div class="mb-6 rounded-lg bg-brand-50 border border-brand-200 text-brand-700 px-4 py-3 text-sm">
            {{ session("success") }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    @yield("content")
</main>

<footer class="text-center text-xs text-gray-400 py-6">
    CakraNik — Perputaran Bahan Organik untuk Kota Sirkular
</footer>
</body>
</html>