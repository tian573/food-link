<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Bank Map - Food Link</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map { 
            height: 300px; 
            width: 100%; 
            border-radius: 12px; 
        }
        .food-bank-item {
            cursor: pointer;
            transition: all 0.2s;
        }
        .food-bank-item:hover {
            transform: translateX(4px);
        }
        .food-bank-item.selected {
            background-color: #dcfce7;
            border-color: #16a34a;
        }
    </style>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.png') }}" alt="Food Link Logo" class="w-12 h-12 object-contain">
                <span class="font-semibold text-xl text-gray-800">Food Link</span>
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

    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
            <div id="map"></div>
        </div>

        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-900 mb-3">Daftar Food Bank Sekitarmu</h2>
            <div class="relative">
                <input 
                    type="text" 
                    id="searchInput"
                    placeholder="Cari food bank..." 
                    class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                >
                <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <div id="foodBankList" class="space-y-3 mb-6">
            <!-- Food bank items will be dynamically inserted here -->
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <a href="/" class="bg-pink-200 text-gray-800 px-6 py-4 rounded-lg font-semibold text-center hover:bg-pink-300 transition">
                Back
            </a>
            <button 
                id="donateBtn"
                class="bg-green-500 text-white px-6 py-4 rounded-lg font-semibold hover:bg-green-600 transition disabled:bg-gray-300 disabled:cursor-not-allowed"
                disabled
            >
                Donasi
            </button>
        </div>
    </div>

    <script>
        const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
        
        const foodBanks = [
            {id: 1, name: "Food Bank A", lat: -6.1867, lng: 106.8348, address: "Jl. Sudirman No. 1, Jakarta Pusat"},
            {id: 2, name: "Food Bank B", lat: -6.2615, lng: 106.7809, address: "Jl. Gatot Subroto, Jakarta Selatan"},
            {id: 3, name: "Food Bank C", lat: -6.1385, lng: 106.8631, address: "Jl. Sunter, Jakarta Utara"},
            {id: 4, name: "Food Bank D", lat: -6.1668, lng: 106.7503, address: "Jl. Puri Indah, Jakarta Barat"},
            {id: 5, name: "Food Bank E", lat: -6.2250, lng: 106.9004, address: "Jl. Rawamangun, Jakarta Timur"},
            {id: 6, name: "Food Bank F", lat: -6.1950, lng: 106.8200, address: "Jl. Thamrin, Jakarta Pusat"},
            {id: 7, name: "Food Bank G", lat: -6.2400, lng: 106.7900, address: "Jl. Senopati, Jakarta Selatan"}
        ];

        let map;
        let markers = [];
        let userLocation = null;
        let selectedFoodBank = null;

        function initMap() {
            map = L.map('map').setView([-6.2088, 106.8456], 11);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        userLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        
                        const userIcon = L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        });
                        
                        L.marker([userLocation.lat, userLocation.lng], {icon: userIcon})
                            .addTo(map)
                            .bindPopup('<b>Lokasi Anda</b>');
                        
                        map.setView([userLocation.lat, userLocation.lng], 12);
                        
                        renderFoodBanks();
                    },
                    (error) => {
                        console.error('Error getting location:', error);
                        alert('Tidak dapat mengakses lokasi Anda. Izinkan akses lokasi untuk melihat jarak food bank terdekat.');
                        renderFoodBanks();
                    }
                );
            } else {
                alert('Browser Anda tidak mendukung geolocation.');
                renderFoodBanks();
            }

            addFoodBankMarkers();
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; 
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            const distance = R * c;
            return distance;
        }

        function addFoodBankMarkers() {
            const greenIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            foodBanks.forEach((fb, index) => {
                const marker = L.marker([fb.lat, fb.lng], {icon: greenIcon})
                    .addTo(map)
                    .bindPopup(`<b>${fb.name}</b><br>${fb.address}`);
                
                marker.on('click', () => selectFoodBank(index));
                markers.push(marker);
            });
        }

        function renderFoodBanks(filteredBanks = null) {
            const banksToRender = filteredBanks || foodBanks;
            const listContainer = document.getElementById('foodBankList');
            
            const banksWithDistance = banksToRender.map((fb, index) => {
                let distance = null;
                if (userLocation) {
                    distance = calculateDistance(
                        userLocation.lat, 
                        userLocation.lng, 
                        fb.lat, 
                        fb.lng
                    );
                }
                return {...fb, originalIndex: foodBanks.indexOf(fb), distance};
            });

            if (userLocation) {
                banksWithDistance.sort((a, b) => (a.distance || 999) - (b.distance || 999));
            }

            listContainer.innerHTML = banksWithDistance.map((fb) => `
                <div class="food-bank-item bg-white rounded-lg p-4 border-2 border-gray-200 ${selectedFoodBank === fb.originalIndex ? 'selected' : ''}" 
                     onclick="selectFoodBank(${fb.originalIndex})"
                     data-index="${fb.originalIndex}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-bold text-lg text-gray-900">${fb.name} ${fb.distance ? `(${fb.distance.toFixed(1)} km)` : ''}</h3>
                            <p class="text-sm text-gray-600 mt-1">${fb.address}</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            `).join('');
        }

        function selectFoodBank(index) {
            selectedFoodBank = index;
            
            document.querySelectorAll('.food-bank-item').forEach(item => {
                item.classList.remove('selected');
            });
            document.querySelector(`[data-index="${index}"]`).classList.add('selected');
            
            document.getElementById('donateBtn').disabled = false;
            
            const fb = foodBanks[index];
            map.setView([fb.lat, fb.lng], 14);
            markers[index].openPopup();
        }

        document.getElementById('searchInput').addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            if (searchTerm === '') {
                renderFoodBanks();
            } else {
                const filtered = foodBanks.filter(fb => 
                    fb.name.toLowerCase().includes(searchTerm) || 
                    fb.address.toLowerCase().includes(searchTerm)
                );
                renderFoodBanks(filtered);
            }
        });

        document.getElementById('donateBtn').addEventListener('click', () => {
            if (selectedFoodBank !== null) {
                if (!isAuthenticated) {
                    window.location.href = '/login';
                } else {
                    window.location.href = `/form?foodbank=${selectedFoodBank}`;
                }
            }
        });

        initMap();
    </script>
</body>
</html>