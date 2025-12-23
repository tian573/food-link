<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi Saya - Food Link</title>
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

    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Donasi Saya</h1>
            <a href="/" class="text-green-600 hover:text-green-700 font-semibold">
                ← Kembali ke Home
            </a>
        </div>

        @if($donations->isEmpty())
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="mb-6 flex justify-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Belum Ada Donasi</h2>
                <p class="text-gray-600 mb-8">Anda belum melakukan donasi. Mulai berbagi makanan sekarang!</p>
                <a href="/form" class="inline-block bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                    Mulai Donasi
                </a>
            </div>
        @else
            <div class="mb-4 flex items-center justify-between">
                <p class="text-gray-600">Total: <span class="font-bold text-gray-900">{{ $donations->count() }}</span> donasi</p>
                <a href="/form" class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
                    + Donasi Baru
                </a>
            </div>

            <div class="space-y-4">
                @foreach($donations as $donation)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="md:flex">
                        <!-- Food Image -->
                        <div class="md:w-48 h-48 md:h-auto bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center flex-shrink-0">
                            @if($donation->food_photo)
                                @php
                                    $imagePath = $donation->food_photo;
                                    $fullPath = storage_path('app/public/' . $imagePath);
                                    $imageExists = file_exists($fullPath);
                                @endphp
                                
                                @if($imageExists)
                                    <img src="{{ asset('storage/' . $donation->food_photo) }}" 
                                         alt="{{ $donation->food_name }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null; this.parentElement.innerHTML='<svg class=\'w-20 h-20 text-green-600\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\' /></svg>';">
                                @else
                                    <div class="text-center p-4">
                                        <svg class="w-20 h-20 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-xs text-gray-500">Gambar tidak tersedia</p>
                                    </div>
                                @endif
                            @else
                                <svg class="w-20 h-20 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                </svg>
                            @endif
                        </div>

                        <!-- Donation Details -->
                        <div class="flex-1 p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $donation->food_name }}</h3>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs rounded {{ $donation->food_type == 'Analisis AI' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ $donation->food_type }}
                                        </span>
                                        <span class="px-2 py-1 text-xs rounded {{ $donation->status == 'completed' ? 'bg-green-100 text-green-700' : ($donation->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : ($donation->status == 'picked_up' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700')) }}">
                                            {{ ucfirst($donation->status) }}
                                        </span>
                                    </div>
                                </div>
                                <button onclick="toggleDetails('donation-{{ $donation->id }}')" class="text-green-600 hover:text-green-700 p-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500">Berat</p>
                                    <p class="font-semibold text-gray-900">{{ $donation->estimated_weight }} kg</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Kondisi</p>
                                    <p class="font-semibold text-gray-900">{{ $donation->condition }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Expired</p>
                                    <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($donation->expiry_date)->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Pickup</p>
                                    <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($donation->pickup_date)->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            <!-- Collapsible Details -->
                            <div id="donation-{{ $donation->id }}" class="hidden mt-6 pt-6 border-t border-gray-200">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="space-y-3">
                                        <h4 class="font-bold text-gray-900 mb-3">Detail Makanan</h4>
                                        
                                        @if($donation->nutritional_value)
                                        <div>
                                            <p class="text-gray-500 text-sm">Nilai Gizi</p>
                                            <p class="text-gray-900">{{ $donation->nutritional_value }}</p>
                                        </div>
                                        @endif

                                        @if($donation->predicted_expiry)
                                        <div>
                                            <p class="text-gray-500 text-sm">Prediksi Expired</p>
                                            <p class="text-gray-900">{{ $donation->predicted_expiry }}</p>
                                        </div>
                                        @endif

                                        <div>
                                            <p class="text-gray-500 text-sm">Waktu Pickup</p>
                                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($donation->pickup_date)->format('d M Y') }} • {{ \Carbon\Carbon::parse($donation->pickup_time)->format('H:i') }}</p>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <h4 class="font-bold text-gray-900 mb-3">Lokasi & Kontak</h4>
                                        
                                        <div>
                                            <p class="text-gray-500 text-sm">Alamat</p>
                                            <p class="text-gray-900">{{ $donation->address }}</p>
                                        </div>

                                        <div>
                                            <p class="text-gray-500 text-sm">Kota</p>
                                            <p class="text-gray-900">{{ $donation->city }}</p>
                                        </div>

                                        @if($donation->selected_foodbank)
                                        <div>
                                            <p class="text-gray-500 text-sm">Food Bank</p>
                                            <p class="text-gray-900">{{ $donation->selected_foodbank }}</p>
                                        </div>
                                        @endif

                                        <div>
                                            <p class="text-gray-500 text-sm">Nomor Telepon</p>
                                            <p class="text-gray-900">{{ $donation->contact_number }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                                    <p class="text-sm text-gray-500">
                                        Dibuat: {{ $donation->created_at->format('d M Y, H:i') }}
                                    </p>
                                    <div class="flex gap-2">
                                        @if(in_array($donation->status, ['pending', 'approved', 'picked_up']))
                                            @if($donation->status == 'picked_up')
                                            <form action="{{ route('donation.updateStatus', $donation->id) }}" method="POST" class="inline" onsubmit="return confirm('Konfirmasi donasi selesai?')">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="text-green-600 hover:text-green-700 text-sm font-semibold">
                                                    ✓ Konfirmasi Selesai
                                                </button>
                                            </form>
                                            @endif

                                            @if($donation->status == 'pending')
                                            <form action="{{ route('donation.updateStatus', $donation->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan donasi ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-semibold">
                                                    Batalkan Donasi
                                                </button>
                                            </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function toggleDetails(id) {
            const element = document.getElementById(id);
            element.classList.toggle('hidden');
        }
    </script>

    @if(session('success'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="successMessage">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('successMessage')?.remove();
        }, 3000);
    </script>
    @endif

    @if(session('error'))
    <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="errorMessage">
        {{ session('error') }}
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('errorMessage')?.remove();
        }, 3000);
    </script>
    @endif
</body>
</html>