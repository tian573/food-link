<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi Berhasil - Food Link</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.png') }}" alt="Food Link Logo" class="w-12 h-12 object-contain">
                <span class="font-semibold text-xl text-gray-800">Food Link</span>
            </div>
            <a href="/profile" class="text-gray-600 hover:text-gray-800">
                @if(Auth::user()->profile_picture)
                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile" class="w-8 h-8 rounded-full object-cover">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                @endif
            </a>
        </div>
    </header>

    <div class="container mx-auto px-4 py-16 max-w-2xl">
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <div class="mb-6 flex justify-center">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">Donasi Berhasil!</h1>
            <p class="text-gray-600 mb-8 text-lg">
                Terima kasih telah berkontribusi mengurangi food waste. <br>
                Donasi Anda akan segera diproses oleh food bank terpilih.
            </p>

            <div class="flex gap-4 justify-center">
                <a href="/" 
                   class="bg-white border-2 border-green-600 text-green-600 px-8 py-3 rounded-lg font-semibold hover:bg-green-50 transition">
                    Kembali ke Home
                </a>
                <a href="/my-donations" 
                   class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                    Lihat Donasi Saya
                </a>
            </div>
        </div>
    </div>
</body>
</html>