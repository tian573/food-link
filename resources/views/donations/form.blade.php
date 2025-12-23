<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Donasi - Food Link</title>
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
                @if(Auth::check() && Auth::user()->profile_picture)
                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile" class="w-8 h-8 rounded-full object-cover">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                @endif
            </a>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">Donasi Makanan</h1>
            
            <!-- Food Image Placeholder -->
            <div class="mb-6 flex justify-center">
                @if($tempPhotoPath)
                    <img src="{{ asset('storage/' . $tempPhotoPath) }}" alt="Food" class="w-48 h-48 object-cover rounded-xl shadow-md">
                @else
                    <div class="w-48 h-48 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                        <svg class="w-24 h-24 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        </svg>
                    </div>
                @endif
            </div>

            <form action="{{ route('donation.store') }}" method="POST">
                @csrf
                <input type="hidden" name="food_photo" value="{{ $tempPhotoPath }}">
                <input type="hidden" name="food_type" value="{{ $aiAnalysis ? 'Analisis AI' : 'Upload Foto' }}">

                <!-- Informasi Makanan Section -->
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    @if($aiAnalysis)
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded mr-2">✓ AI Analisis</span>
                    @else
                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded mr-2">📸 Upload Foto</span>
                    @endif
                    Informasi Makanan
                </h2>
                
                <div class="space-y-4 mb-6">
                    <!-- Nama Makanan -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nama Makanan</label>
                        @php
                            $foodNameValue = $foodName ?? old('food_name', '');
                            if (isset($aiAnalysis['foodNameIdentified']) && !empty($aiAnalysis['foodNameIdentified'])) {
                                $foodNameValue = $aiAnalysis['foodNameIdentified'];
                            }
                        @endphp
                        <input type="text" name="food_name" 
                               value="{{ $foodNameValue }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               placeholder="Pisang" required>
                        @if(isset($aiAnalysis['servingDetails']))
                        <p class="mt-1 text-xs text-gray-500">📊 {{ $aiAnalysis['servingDetails'] }}</p>
                        @endif
                    </div>

                    <!-- Tanggal Expired -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Tanggal Expired</label>
                        <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               required>
                    </div>

                    <!-- Estimasi Berat & Nilai Gizi (2 columns) -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Estimasi Berat</label>
                            <div class="flex items-center">
                                <input type="number" name="estimated_weight" step="0.01" min="0.1"
                                       value="{{ $estimatedWeight ?? old('estimated_weight', '') }}" 
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                       required>
                                <span class="px-4 py-3 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg text-gray-700 font-semibold">
                                    kg
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Nilai Gizi (Kalori)</label>
                            @php
                                $nutritionalValue = old('nutritional_value', '');
                                
                                if (isset($aiAnalysis['nutritionFacts']['Calories'])) {
                                    $caloriesRaw = $aiAnalysis['nutritionFacts']['Calories'];
                                    if (preg_match('/(\d+(?:\.\d+)?)/', $caloriesRaw, $matches)) {
                                        $nutritionalValue = $matches[1];
                                    }
                                }
                                
                                if (empty($nutritionalValue)) {
                                    $nutritionalValue = '';
                                }
                            @endphp
                            <input type="text" name="nutritional_value" 
                                   value="{{ $nutritionalValue }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                   placeholder="Contoh: 200">
                            
                            @if(isset($aiAnalysis['nutritionFacts']) && is_array($aiAnalysis['nutritionFacts']))
                            <div class="mt-2 p-2 bg-green-50 rounded text-xs text-gray-700">
                                <div class="grid grid-cols-2 gap-1">
                                    <span>🥩 Protein: {{ $aiAnalysis['nutritionFacts']['Protein'] ?? 'N/A' }}</span>
                                    <span>🧈 Lemak: {{ $aiAnalysis['nutritionFacts']['Fat'] ?? 'N/A' }}</span>
                                    <span>🍞 Karbo: {{ $aiAnalysis['nutritionFacts']['Carbs'] ?? 'N/A' }}</span>
                                    <span>🔥 Kalori: {{ $aiAnalysis['nutritionFacts']['Calories'] ?? 'N/A' }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Prediksi Expired & Kondisi (2 columns) -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Prediksi Shelf Life</label>
                            @php
                                $predictedExpiry = old('predicted_expiry', '');
                                
                                if (isset($aiAnalysis['expirationAnalysis']['estimatedShelfLife'])) {
                                    $shelfLife = $aiAnalysis['expirationAnalysis']['estimatedShelfLife'];
                                    $predictedExpiry = $shelfLife;
                                    
                                    if (preg_match('/(\d+)[-–]?(\d+)?\s*(day|days|week|weeks|hari|minggu)/i', $shelfLife, $matches)) {
                                        $number = isset($matches[2]) && $matches[2] ? intval($matches[2]) : intval($matches[1]);
                                        $unit = strtolower($matches[3]);
                                        
                                        if (strpos($unit, 'week') !== false || strpos($unit, 'minggu') !== false) {
                                            $number *= 7;
                                        }
                                        
                                        $calculatedDate = date('d/m/Y', strtotime("+{$number} days"));
                                        $predictedExpiry = $shelfLife . " (sampai ~" . $calculatedDate . ")";
                                    }
                                }
                                
                                if (empty($predictedExpiry)) {
                                    $predictedExpiry = '';
                                }
                            @endphp
                            <input type="text" name="predicted_expiry" readonly
                                   value="{{ $predictedExpiry }}" 
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-600"
                                   placeholder="Akan diisi otomatis dari AI">
                            
                            @if(isset($aiAnalysis['expirationAnalysis']['storageRecommendation']))
                            <p class="mt-1 text-xs text-blue-600">
                                💡 {{ $aiAnalysis['expirationAnalysis']['storageRecommendation'] }}
                            </p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Kondisi</label>
                            <select name="condition" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                    required>
                                <option value="Fresh" selected>Fresh</option>
                                <option value="Busuk">Busuk</option>
                            </select>
                        </div>
                    </div>

                    <!-- Potential Allergens -->
                    @if(isset($aiAnalysis['potentialAllergens']) && is_array($aiAnalysis['potentialAllergens']) && !empty($aiAnalysis['potentialAllergens']))
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                        <p class="text-sm font-semibold text-orange-800 mb-2">⚠️ Potensi Alergen:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($aiAnalysis['potentialAllergens'] as $allergen)
                            <span class="bg-orange-200 text-orange-800 text-xs px-2 py-1 rounded">{{ $allergen }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Lokasi Donasi Section -->
                <h2 class="text-xl font-bold text-gray-900 mb-4">Lokasi Donasi</h2>
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Alamat</label>
                        <textarea name="address" rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                  placeholder="Jalan, Kota" required>{{ old('address') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Kota</label>
                        <input type="text" name="city" 
                               value="{{ old('city') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               placeholder="Jakarta" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Pilih Food Bank
                            <span id="locationStatus" class="text-sm text-gray-500 ml-2"></span>
                        </label>
                        <select name="selected_foodbank" id="foodbankSelect"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                required>
                            <option value="">Mendeteksi lokasi...</option>
                        </select>
                    </div>
                </div>

                <!-- Waktu Pengantaran Section -->
                <h2 class="text-xl font-bold text-gray-900 mb-4">Waktu Pengantaran</h2>
                <div class="space-y-4 mb-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Pilih Tanggal</label>
                            <input type="date" name="pickup_date" 
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('pickup_date') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                   required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Waktu</label>
                            <input type="time" name="pickup_time" 
                                   value="{{ old('pickup_time') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Kontak Section -->
                <h2 class="text-xl font-bold text-gray-900 mb-4">Kontak</h2>
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Nomor Telepon</label>
                    <input type="tel" name="contact_number" 
                           value="{{ old('contact_number') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           placeholder="08123456789" required>
                </div>

                <!-- Submit Buttons -->
                <div class="grid grid-cols-2 gap-4">
                    <a href="/map" 
                       class="bg-gray-200 text-gray-800 px-6 py-4 rounded-lg font-semibold text-center hover:bg-gray-300 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-green-500 text-white px-6 py-4 rounded-lg font-semibold hover:bg-green-600 transition">
                        Kirim Donasi Makanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="successNotif">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => document.getElementById('successNotif')?.remove(), 3000);
    </script>
    @endif

    @if(session('warning'))
    <div class="fixed top-4 right-4 bg-yellow-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="warningNotif">
        {{ session('warning') }}
    </div>
    <script>
        setTimeout(() => document.getElementById('warningNotif')?.remove(), 5000);
    </script>
    @endif

    <script>
        const foodBanks = [
            {id: 0, name: "Food Bank A", lat: -6.1867, lng: 106.8348, address: "Jl. Sudirman No. 1, Jakarta Pusat"},
            {id: 1, name: "Food Bank B", lat: -6.2615, lng: 106.7809, address: "Jl. Gatot Subroto, Jakarta Selatan"},
            {id: 2, name: "Food Bank C", lat: -6.1385, lng: 106.8631, address: "Jl. Sunter, Jakarta Utara"},
            {id: 3, name: "Food Bank D", lat: -6.1668, lng: 106.7503, address: "Jl. Puri Indah, Jakarta Barat"},
            {id: 4, name: "Food Bank E", lat: -6.2250, lng: 106.9004, address: "Jl. Rawamangun, Jakarta Timur"},
            {id: 5, name: "Food Bank F", lat: -6.1950, lng: 106.8200, address: "Jl. Thamrin, Jakarta Pusat"},
            {id: 6, name: "Food Bank G", lat: -6.2400, lng: 106.7900, address: "Jl. Senopati, Jakarta Selatan"}
        ];

        const selectedFoodbank = "{{ $selectedFoodbank ?? '' }}";
        const foodbankSelect = document.getElementById('foodbankSelect');
        const locationStatus = document.getElementById('locationStatus');

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Earth's radius in km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }

        function populateFoodBanks(userLat = null, userLng = null) {
            let banksWithDistance = foodBanks.map(fb => {
                let distance = null;
                if (userLat && userLng) {
                    distance = calculateDistance(userLat, userLng, fb.lat, fb.lng);
                }
                return {...fb, distance};
            });

            // Sort by distance if available
            if (userLat && userLng) {
                banksWithDistance.sort((a, b) => a.distance - b.distance);
                locationStatus.textContent = '(diurutkan berdasarkan jarak terdekat)';
                locationStatus.classList.add('text-green-600');
            } else {
                locationStatus.textContent = '(lokasi tidak terdeteksi)';
                locationStatus.classList.add('text-gray-500');
            }

            // Clear and populate select
            foodbankSelect.innerHTML = '<option value="">Pilih Food Bank</option>';
            
            banksWithDistance.forEach((fb, index) => {
                const option = document.createElement('option');
                const distanceText = fb.distance ? ` (${fb.distance.toFixed(1)} km)` : '';
                const closestBadge = index === 0 && fb.distance ? ' 🎯 Terdekat!' : '';
                
                option.value = `${fb.name}${distanceText}`;
                option.textContent = `${fb.name}${distanceText}${closestBadge}`;
                
                // Select the previously selected foodbank or the one from query
                if (selectedFoodbank && selectedFoodbank === fb.id.toString()) {
                    option.selected = true;
                }
                
                foodbankSelect.appendChild(option);
            });
        }

        // Try to get user location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    populateFoodBanks(position.coords.latitude, position.coords.longitude);
                },
                (error) => {
                    console.error('Location error:', error);
                    populateFoodBanks(); // Populate without distance
                }
            );
        } else {
            populateFoodBanks(); // Populate without distance
        }
    </script>
</body>
</html>