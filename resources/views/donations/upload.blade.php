<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Foto Makanan - Food Link</title>
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

    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">Detail Makanan</h1>
            
            <form action="{{ route('donation.analyze') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                
                <div class="mb-6">
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-green-500 transition-colors duration-200 ease-in-out relative" id="photoUploadArea">
                        
                        <input type="file" name="food_photo" id="food_photo" accept="image/*" class="hidden" required>
                        
                        <div id="photoPreview" class="hidden">
                            <img src="" alt="Preview" class="max-h-64 mx-auto rounded-lg mb-4 pointer-events-none">
                            <button type="button" onclick="removePhoto(event)" class="text-red-500 text-sm hover:text-red-700 font-semibold z-10 relative">
                                Hapus Foto
                            </button>
                        </div>

                        <div id="photoPlaceholder" class="pointer-events-none">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p class="text-gray-600 mb-2 font-medium">Klik atau <span class="text-green-600">Drag & Drop</span> foto di sini</p>
                            <p class="text-sm text-gray-400">PNG, JPG hingga 5MB</p>
                        </div>
                    </div>
                    @error('food_photo')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Makanan</label>
                    <input type="text" name="food_name" value="{{ old('food_name') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           placeholder="Contoh: Pisang" required>
                    @error('food_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Estimasi Berat</label>
                    <div class="flex gap-2">
                        <input type="number" name="estimated_weight" value="{{ old('estimated_weight') }}" 
                               step="0.01" min="0.1"
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               placeholder="2" required>
                        <div class="px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 font-semibold">
                            kg
                        </div>
                    </div>
                    @error('estimated_weight')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="button" onclick="window.history.back()" 
                            class="flex-1 bg-green-100 text-green-600 px-6 py-3 rounded-lg font-semibold hover:bg-green-200 transition">
                        Foto Ulang
                    </button>
                    <button type="submit" id="analyzeBtn"
                            class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                        <span id="btnText">Analisis AI</span>
                        <span id="btnLoading" class="hidden">Menganalisis...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const photoInput = document.getElementById('food_photo');
        const photoUploadArea = document.getElementById('photoUploadArea');
        const photoPreview = document.getElementById('photoPreview');
        const photoPlaceholder = document.getElementById('photoPlaceholder');
        const uploadForm = document.getElementById('uploadForm');
        const analyzeBtn = document.getElementById('analyzeBtn');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');

        // --- Drag and Drop Logic ---

        // Prevent default browser behaviors for drag events
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            photoUploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            photoUploadArea.addEventListener(eventName, highlight, false);
        });

        // Remove highlight when item leaves or is dropped
        ['dragleave', 'drop'].forEach(eventName => {
            photoUploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            photoUploadArea.classList.add('border-green-500', 'bg-green-50', 'ring-2', 'ring-green-200');
            photoUploadArea.classList.remove('border-gray-300');
        }

        function unhighlight() {
            photoUploadArea.classList.remove('border-green-500', 'bg-green-50', 'ring-2', 'ring-green-200');
            photoUploadArea.classList.add('border-gray-300');
        }

        // Handle the dropped file
        photoUploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                // Assign dropped files to the input element
                photoInput.files = files;
                handleFiles(files[0]);
            }
        }

        // --- Click Logic ---

        // Trigger file input when clicking the area (unless clicking delete button)
        photoUploadArea.addEventListener('click', (e) => {
            // Check if click was NOT on the delete button
            if (!e.target.closest('button') && photoPlaceholder.classList.contains('hidden') === false) {
                photoInput.click();
            }
        });

        // Handle file selection via Click
        photoInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFiles(e.target.files[0]);
            }
        });

        // Common function to process and preview the file
        function handleFiles(file) {
            // Validate image type
            if (!file.type.startsWith('image/')) {
                alert('Mohon upload file gambar saja (JPG/PNG).');
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                photoPreview.querySelector('img').src = e.target.result;
                photoPlaceholder.classList.add('hidden');
                photoPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        function removePhoto(e) {
            // Stop event bubbling so it doesn't trigger the area click
            if (e) e.stopPropagation();
            
            photoInput.value = '';
            photoPreview.classList.add('hidden');
            photoPlaceholder.classList.remove('hidden');
        }

        uploadForm.addEventListener('submit', () => {
            analyzeBtn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
        });
    </script>
</body>
</html>