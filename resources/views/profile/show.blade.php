<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Food Link</title>
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

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <!-- View Mode -->
            <div id="viewMode" class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <!-- Profile Header -->
                <div class="bg-white p-6 text-center border-b">
                    <div class="relative w-20 h-20 mx-auto mb-3">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="w-20 h-20 rounded-full object-cover">
                        @else
                            <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                    @if($user->usertype === 'admin')
                        <span class="inline-block mt-2 px-3 py-1 bg-red-100 text-red-600 text-xs font-semibold rounded-full">Admin</span>
                    @endif
                    <button onclick="toggleEditMode()" class="mt-3 px-6 py-2 text-sm text-blue-600 border border-blue-600 rounded-full hover:bg-blue-50 transition">
                        Edit User Profile
                    </button>
                </div>

                <!-- Profile Details -->
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama</label>
                        <p class="text-gray-800">{{ $user->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <p class="text-gray-800">{{ $user->email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Alamat</label>
                        <p class="text-gray-800">{{ $user->address ?? 'Belum diisi' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">No. Telpon</label>
                        <p class="text-gray-800">{{ $user->phone ?? 'Belum diisi' }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="p-6 pt-2 space-y-3">
                    @if($user->usertype === 'admin')
                        <!-- Admin Dashboard Button -->
                        <button onclick="window.location.href='{{ route('admin.dashboard') }}'" class="w-full bg-red-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-red-700 transition flex items-center justify-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Admin Dashboard</span>
                        </button>
                    @endif

                    <!-- My Donations Button -->
                    <button onclick="window.location.href='{{ route('donation.myDonations') }}'" class="w-full bg-green-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-green-700 transition flex items-center justify-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <span>My Donations</span>
                    </button>

                    <button onclick="window.location.href='/'" class="w-full bg-pink-300 text-gray-800 px-6 py-3 rounded-full font-semibold hover:bg-pink-400 transition">
                        Back
                    </button>
                    
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full bg-gray-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-gray-700 transition">
                            Logout
                        </button>
                    </form>
                    
                    @if($user->usertype !== 'admin')
                    <button onclick="showDeleteModal()" class="w-full bg-red-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-red-700 transition">
                        Delete Account
                    </button>
                    @endif
                </div>
            </div>

            <!-- Edit Mode -->
            <div id="editMode" class="bg-white rounded-2xl shadow-lg overflow-hidden hidden">
                <h2 class="text-2xl font-bold text-center text-gray-800 py-6 border-b">Profile</h2>

                @if(session('success'))
                    <div class="mx-6 mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mx-6 mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6 space-y-4">
                        <!-- Profile Picture Upload -->
                        <div class="flex flex-col items-center mb-4">
                            <div class="relative">
                                <div id="imagePreview" class="w-24 h-24 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                    @if($user->profile_picture)
                                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="w-full h-full object-cover" id="currentImage">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="defaultIcon">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    @endif
                                </div>
                                <label for="profile_picture" class="absolute bottom-0 right-0 bg-blue-600 text-white p-2 rounded-full cursor-pointer hover:bg-blue-700 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </label>
                            </div>
                            <input 
                                type="file" 
                                name="profile_picture" 
                                id="profile_picture"
                                accept="image/*"
                                class="hidden"
                                onchange="previewImage(event)"
                            >
                            <p class="text-sm text-gray-500 mt-2">Upload foto profil</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama</label>
                            <input 
                                type="text" 
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                required
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                            <input 
                                type="email" 
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                required
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Alamat</label>
                            <input 
                                type="text" 
                                name="address"
                                value="{{ old('address', $user->address) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">No. Telpon</label>
                            <input 
                                type="text" 
                                name="phone"
                                value="{{ old('phone', $user->phone) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 px-6 pb-6">
                        <button 
                            type="button"
                            onclick="toggleEditMode()"
                            class="bg-pink-300 text-gray-800 px-6 py-3 rounded-full font-semibold hover:bg-pink-400 transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="bg-green-500 text-white px-6 py-3 rounded-full font-semibold hover:bg-green-600 transition"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 max-w-sm mx-4 shadow-xl">
            <div class="text-center mb-4">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Delete Account</h3>
                <p class="text-sm text-gray-600">
                    Are you sure you want to delete your account? This action cannot be undone and all your data will be permanently removed.
                </p>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <button 
                    onclick="hideDeleteModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300 transition"
                >
                    Cancel
                </button>
                <form method="POST" action="{{ route('profile.delete') }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit"
                        class="w-full px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition"
                    >
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleEditMode() {
            const viewMode = document.getElementById('viewMode');
            const editMode = document.getElementById('editMode');
            
            viewMode.classList.toggle('hidden');
            editMode.classList.toggle('hidden');
        }

        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
                }
                reader.readAsDataURL(file);
            }
        }

        function showDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideDeleteModal();
            }
        });

        @if($errors->any())
            document.getElementById('viewMode').classList.add('hidden');
            document.getElementById('editMode').classList.remove('hidden');
        @endif
    </script>
</body>
</html>