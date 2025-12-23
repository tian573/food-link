<!DOCTYPE html>
<html lang="id" style="scroll-behavior: smooth;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Link - Selamatkan Makanan, Bantu Sesama</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map { height: 300px; width: 100%; border-radius: 12px; }
        .search-container {
            position: relative;
            z-index: 1000;
        }
        .search-results {
            max-height: 300px;
            overflow-y: auto;
            z-index: 1001;
        }
        .location-item {
            cursor: pointer;
            transition: all 0.2s;
        }
        .location-item:hover {
            background-color: #f0fdf4;
        }
        .leaflet-container {
            z-index: 1;
        }
    </style>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.png') }}" alt="Food Link Logo" class="w-12 h-12 object-contain">
                <span class="font-semibold text-gray-800">Food Link</span>
            </div>
            <a href="{{ Auth::check() ? '/profile' : '/login' }}" class="text-gray-600 hover:text-gray-800">
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

    <section class="container mx-auto px-4 py-8">
        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-8 flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    Selamatkan<br>
                    Makanan.<br>
                    Bantu Sesama.
                </h1>
                <p class="text-gray-600 mb-6 max-w-md">
                    Mulai donasi makanan ke food bank terdekat untuk mengurangi food waste
                </p>
                <div class="flex space-x-4">
                    <a href="{{ Auth::check() ? '/form' : '/register' }}" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                        Mulai Donasi Sekarang
                    </a>
                    <a href="#map-section" class="bg-white text-green-600 px-6 py-3 rounded-lg font-semibold border-2 border-green-600 hover:bg-green-50 transition">
                        Lihat Food Bank
                    </a>
                </div>
            </div>
            <div class="flex-1 flex justify-end">
                <img src="{{ asset('images/image1.png') }}" alt="Food donation" class="w-64 h-64 object-cover rounded-2xl shadow-lg">
            </div>
        </div>
    </section>

    <section id="map-section" class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Temukan Food Bank Terdekat</h2>
        
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
            <div class="flex space-x-2">
                <div class="flex-1 relative search-container">
                    <input 
                        type="text" 
                        id="searchInput"
                        placeholder="Cari berdasarkan nama atau kota food bank..." 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                    <div id="searchResults" class="absolute w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
                        <div class="search-results" id="resultsList">
                        </div>
                    </div>
                </div>
                <a href="/map" class="bg-white text-green-600 px-6 py-2 rounded-lg border-2 border-green-600 font-semibold hover:bg-green-50 transition whitespace-nowrap">
                    Lihat Semua di Map
                </a>
            </div>
        </div>

        <div id="map" class="shadow-lg"></div>
    </section>

    <section id="cara-kerja-section" class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Cara Kerja</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <img src="{{ asset('images/image3.png') }}" alt="Register Icon" class="w-10 h-10 object-contain">
                </div>
                <h3 class="font-bold text-lg mb-2">Daftarkan Makanan yang Ingin Didonasikan</h3>
                <p class="text-gray-600 text-sm">Isi form donasi yang telah disediakan</p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <img src="{{ asset('images/image4.png') }}" alt="Location Icon" class="w-10 h-10 object-contain">
                </div>
                <h3 class="font-bold text-lg mb-2">Pilih Food Bank Terdekat</h3>
                <p class="text-gray-600 text-sm">Lihat map dan pilih lokasi yang paling dekat</p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <img src="{{ asset('images/image5.png') }}" alt="Donate Icon" class="w-10 h-10 object-contain">
                </div>
                <h3 class="font-bold text-lg mb-2">Serahkan & Bantu Sesama</h3>
                <p class="text-gray-600 text-sm">Food bank menyalurkan makanan ke yang membutuhkan</p>
            </div>
        </div>
    </section>

    <section id="statistik-section" class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Statistik</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/image6.png') }}" alt="Food Icon" class="w-16 h-16 object-contain">
                </div>
                <div class="text-4xl font-bold text-gray-900 mb-2">1.2193 <span class="text-lg">kg</span></div>
                <p class="text-gray-600">Makanan Didonasikan</p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/image7.png') }}" alt="Food Bank Icon" class="w-16 h-16 object-contain">
                </div>
                <div class="text-4xl font-bold text-gray-900 mb-2">69</div>
                <p class="text-gray-600">Food Bank Aktif</p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/image8.png') }}" alt="Heart Icon" class="w-16 h-16 object-contain">
                </div>
                <div class="text-4xl font-bold text-gray-900 mb-2">1000+</div>
                <p class="text-gray-600">Donasi Diterumpah</p>
            </div>
        </div>
    </section>

    <section class="container mx-auto px-4 py-12">
        <div class="bg-green-600 rounded-2xl p-8 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">Bersama, Kita Bisa Kurangi Food Waste di Kota Besar Bersama</h2>
            <p class="text-lg mb-6 opacity-90">Setiap donasi makanan yang Anda berikan dapat membantu mengurangi food waste dan memberikan manfaat bagi mereka yang membutuhkan</p>
            <a href="{{ Auth::check() ? '/form' : '/register' }}" class="inline-block bg-white text-green-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Mulai Donasi Sekarang
            </a>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Food Bank Logo" class="w-10 h-10 object-contain">
                        <span class="font-semibold">Food Link</span>
                    </div>
                    <p class="text-gray-400 text-sm">Selamatkan makanan, bantu sesama</p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Tentang</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white">Tentang Kami</a></li>
                        <li><a href="#cara-kerja-section" class="hover:text-white">Cara Kerja</a></li>
                        <li><a href="#statistik-section" class="hover:text-white">Statistik</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="{{ Auth::check() ? '/profile' : '/login' }}" class="hover:text-white">Profile</a></li>
                        <li><a href="{{ Auth::check() ? '/form' : '/login' }}" class="hover:text-white">Donasi</a></li>
                        <li><a href="/map" class="hover:text-white">Food Bank</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Ikuti Kami</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; 2025 Food Link. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        const foodBanks = [
            {name: "Food Bank Jakarta Pusat", lat: -6.1867, lng: 106.8348, city: "Jakarta Pusat", address: "Jl. Sudirman No. 1"},
            {name: "Food Bank Jakarta Selatan", lat: -6.2615, lng: 106.7809, city: "Jakarta Selatan", address: "Jl. Gatot Subroto"},
            {name: "Food Bank Jakarta Utara", lat: -6.1385, lng: 106.8631, city: "Jakarta Utara", address: "Jl. Sunter"},
            {name: "Food Bank Jakarta Barat", lat: -6.1668, lng: 106.7503, city: "Jakarta Barat", address: "Jl. Puri Indah"},
            {name: "Food Bank Jakarta Timur", lat: -6.2250, lng: 106.9004, city: "Jakarta Timur", address: "Jl. Rawamangun"}
        ];

        let map;
        let markers = [];
        let currentPopup = null;

        map = L.map('map').setView([-6.2088, 106.8456], 11);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const greenIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        foodBanks.forEach(function(fb) {
            const marker = L.marker([fb.lat, fb.lng], {icon: greenIcon})
                .addTo(map)
                .bindPopup(`<b>${fb.name}</b><br>${fb.address}, ${fb.city}`);
            
            markers.push({marker: marker, data: fb});
        });

        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        const resultsList = document.getElementById('resultsList');

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            
            if (searchTerm === '') {
                searchResults.classList.add('hidden');
                return;
            }

            const filtered = foodBanks.filter(fb => 
                fb.name.toLowerCase().includes(searchTerm) || 
                fb.city.toLowerCase().includes(searchTerm) ||
                fb.address.toLowerCase().includes(searchTerm)
            );

            if (filtered.length > 0) {
                displaySearchResults(filtered);
                searchResults.classList.remove('hidden');
            } else {
                resultsList.innerHTML = '<div class="p-4 text-gray-500 text-center">Tidak ada hasil ditemukan</div>';
                searchResults.classList.remove('hidden');
            }
        });

        function displaySearchResults(results) {
            resultsList.innerHTML = results.map((fb, index) => `
                <div class="location-item p-4 border-b border-gray-200 last:border-b-0" onclick="selectLocation(${foodBanks.indexOf(fb)})">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mr-3 mt-1 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">${fb.name}</h4>
                            <p class="text-sm text-gray-600 mt-1">${fb.address}, ${fb.city}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function selectLocation(index) {
            const fb = foodBanks[index];
            const markerData = markers[index];
            
            searchResults.classList.add('hidden');
            searchInput.value = fb.name;
            
            map.setView([fb.lat, fb.lng], 15);
            
            markerData.marker.openPopup();
            
            document.getElementById('map-section').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });

        searchResults.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    </script>
</body>
</html>