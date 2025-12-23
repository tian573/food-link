<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Food Link</title>
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
            <a href="/" class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                Back to Home
            </a>
        </div>
    </header>

    <div class="container mx-auto px-4 py-12">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <!-- Logo Section -->
                <div class="bg-gradient-to-r from-green-50 to-blue-50 p-8 text-center">
                    <div class="flex justify-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Food Link Logo" class="w-20 h-20 object-contain">
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Join Food Link</h2>
                    <p class="text-gray-600 mt-2">Start making a difference today</p>
                </div>

                <!-- Form Section -->
                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Name</label>
                            <input 
                                id="name" 
                                type="text" 
                                name="name" 
                                value="{{ old('name') }}"
                                required 
                                autofocus 
                                autocomplete="name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Enter your full name"
                            >
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                required 
                                autocomplete="username"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Enter your email"
                            >
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">Phone</label>
                            <input 
                                id="phone" 
                                type="text" 
                                name="phone" 
                                value="{{ old('phone') }}"
                                required 
                                autocomplete="phone"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Enter your phone number"
                            >
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-sm font-bold text-gray-700 mb-2">Address</label>
                            <input 
                                id="address" 
                                type="text" 
                                name="address" 
                                value="{{ old('address') }}"
                                required 
                                autocomplete="address"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Enter your address"
                            >
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="new-password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Create a password"
                            >
                        </div>

                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Confirm Password</label>
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Confirm your password"
                            >
                        </div>

                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div class="mb-6">
                                <label for="terms" class="flex items-start">
                                    <input 
                                        type="checkbox" 
                                        name="terms" 
                                        id="terms" 
                                        required
                                        class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 mt-1"
                                    >
                                    <span class="ml-2 text-sm text-gray-600">
                                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-green-600 hover:text-green-700 font-medium underline">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-green-600 hover:text-green-700 font-medium underline">'.__('Privacy Policy').'</a>',
                                        ]) !!}
                                    </span>
                                </label>
                            </div>
                        @endif

                        <button 
                            type="submit"
                            class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition"
                        >
                            Register
                        </button>

                        <div class="mt-6 text-center">
                            <p class="text-sm text-gray-600">
                                Already have an account? 
                                <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 font-semibold">
                                    Login here
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gray-900 text-white py-6 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400 text-sm">&copy; 2024 Food Link. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>