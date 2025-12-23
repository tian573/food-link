<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Food Link</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.png') }}" alt="Food Link Logo" class="w-12 h-12 object-contain">
                <span class="font-semibold text-xl text-gray-800">Food Link Admin</span>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600 text-sm">Admin: {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-700 font-semibold text-sm">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Tab Navigation -->
        <div class="mb-6 flex space-x-2 border-b border-gray-200">
            <button onclick="switchTab('donations')" id="tab-donations" class="tab-button active px-6 py-3 font-semibold border-b-2 border-green-600 text-green-600">
                Donations ({{ $donations->count() }})
            </button>
            <button onclick="switchTab('users')" id="tab-users" class="tab-button px-6 py-3 font-semibold border-b-2 border-transparent text-gray-600 hover:text-gray-800">
                Users ({{ $users->count() }})
            </button>
        </div>

        <!-- Donations Tab -->
        <div id="content-donations" class="tab-content">
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">All Donations</h2>
                
                <!-- Status Filter -->
                <div class="flex space-x-2 mb-4">
                    <button onclick="filterDonations('all')" class="filter-btn active px-4 py-2 rounded-lg bg-green-600 text-white font-semibold">
                        All ({{ $donations->count() }})
                    </button>
                    <button onclick="filterDonations('pending')" class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold">
                        Pending ({{ $donations->where('status', 'pending')->count() }})
                    </button>
                    <button onclick="filterDonations('approved')" class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold">
                        Approved ({{ $donations->where('status', 'approved')->count() }})
                    </button>
                    <button onclick="filterDonations('picked_up')" class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold">
                        Picked Up ({{ $donations->where('status', 'picked_up')->count() }})
                    </button>
                    <button onclick="filterDonations('completed')" class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold">
                        Completed ({{ $donations->where('status', 'completed')->count() }})
                    </button>
                    <button onclick="filterDonations('cancelled')" class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold">
                        Cancelled ({{ $donations->where('status', 'cancelled')->count() }})
                    </button>
                </div>

                @if($donations->isEmpty())
                    <div class="text-center py-12">
                        <p class="text-gray-500">Belum ada donasi</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($donations as $donation)
                        <div class="donation-item border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition" data-status="{{ $donation->status }}">
                            <div class="md:flex">
                                <!-- Food Image -->
                                <div class="md:w-48 h-48 md:h-auto bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center flex-shrink-0">
                                    @if($donation->food_photo)
                                        <img src="{{ asset('storage/' . $donation->food_photo) }}" 
                                             alt="{{ $donation->food_name }}" 
                                             class="w-full h-full object-cover"
                                             onerror="this.onerror=null; this.parentElement.innerHTML='<svg class=\'w-20 h-20 text-green-600\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\' /></svg>';">
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
                                            <p class="text-sm text-gray-600 mb-2">Donor: <span class="font-semibold">{{ $donation->user->name }}</span></p>
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2 py-1 text-xs rounded {{ $donation->food_type == 'Analisis AI' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                                    {{ $donation->food_type }}
                                                </span>
                                                <span class="px-2 py-1 text-xs rounded {{ $donation->status == 'completed' ? 'bg-green-100 text-green-700' : ($donation->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : ($donation->status == 'picked_up' ? 'bg-blue-100 text-blue-700' : ($donation->status == 'approved' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700'))) }}">
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
                                        <div class="grid md:grid-cols-2 gap-6 mb-6">
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
                                                    <p class="text-gray-500 text-sm">Donor</p>
                                                    <p class="text-gray-900 font-semibold">{{ $donation->user->name }}</p>
                                                    <p class="text-gray-600 text-sm">{{ $donation->user->email }}</p>
                                                </div>

                                                <div>
                                                    <p class="text-gray-500 text-sm">Alamat</p>
                                                    <p class="text-gray-900">{{ $donation->address }}, {{ $donation->city }}</p>
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

                                        <!-- Admin Actions -->
                                        <div class="pt-4 border-t border-gray-200">
                                            <p class="text-sm text-gray-500 mb-3">
                                                Dibuat: {{ $donation->created_at->format('d M Y, H:i') }}
                                            </p>
                                            
                                            <div class="flex flex-wrap gap-2">
                                                @if($donation->status == 'pending')
                                                    <form action="{{ route('admin.donations.updateStatus', $donation->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-purple-700">
                                                            ✓ Approve
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(in_array($donation->status, ['pending', 'approved']))
                                                    <form action="{{ route('admin.donations.updateStatus', $donation->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="picked_up">
                                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">
                                                            📦 Mark Picked Up
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($donation->status == 'picked_up')
                                                    <form action="{{ route('admin.donations.updateStatus', $donation->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700">
                                                            ✓ Mark Completed
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(!in_array($donation->status, ['completed', 'cancelled']))
                                                    <form action="{{ route('admin.donations.updateStatus', $donation->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700">
                                                            ✕ Cancel
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('admin.donations.delete', $donation->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this donation?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700">
                                                        🗑️ Delete
                                                    </button>
                                                </form>
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
        </div>

        <!-- Users Tab -->
        <div id="content-users" class="tab-content hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">All Users</h2>
                
                @if($users->isEmpty())
                    <div class="text-center py-12">
                        <p class="text-gray-500">Belum ada pengguna</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">User</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Phone</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Donations</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Registered</th>
                                    <th class="text-center py-3 px-4 font-semibold text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center space-x-3">
                                            @if($user->profile_picture)
                                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                                    <span class="text-green-600 font-semibold">{{ substr($user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                                @if($user->usertype === 'admin')
                                                    <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded">Admin</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-gray-600">{{ $user->email }}</td>
                                    <td class="py-4 px-4 text-gray-600">{{ $user->phone ?? '-' }}</td>
                                    <td class="py-4 px-4">
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                                            {{ $user->donations_count }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-gray-600 text-sm">{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="py-4 px-4 text-center">
                                        @if($user->usertype !== 'admin')
                                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user and all their donations?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-700 font-semibold text-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-sm">Protected</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-green-600', 'text-green-600');
                button.classList.add('border-transparent', 'text-gray-600');
            });
            
            // Show selected tab content
            document.getElementById('content-' + tab).classList.remove('hidden');
            
            // Add active class to selected tab button
            const activeButton = document.getElementById('tab-' + tab);
            activeButton.classList.add('active', 'border-green-600', 'text-green-600');
            activeButton.classList.remove('border-transparent', 'text-gray-600');
        }

        function toggleDetails(id) {
            const element = document.getElementById(id);
            element.classList.toggle('hidden');
        }

        function filterDonations(status) {
            const donations = document.querySelectorAll('.donation-item');
            const filterButtons = document.querySelectorAll('.filter-btn');
            
            // Update button styles
            filterButtons.forEach(btn => {
                btn.classList.remove('bg-green-600', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });
            event.target.classList.remove('bg-gray-200', 'text-gray-700');
            event.target.classList.add('bg-green-600', 'text-white');
            
            // Filter donations
            donations.forEach(donation => {
                if (status === 'all' || donation.dataset.status === status) {
                    donation.style.display = 'block';
                } else {
                    donation.style.display = 'none';
                }
            });
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