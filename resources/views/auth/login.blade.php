<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Food Link</title>
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
                    <h2 class="text-2xl font-bold text-gray-900">Welcome Back!</h2>
                    <p class="text-gray-600 mt-2">Login to continue donating</p>
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

                    @session('status')
                        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                            {{ $value }}
                        </div>
                    @endsession

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                required 
                                autofocus 
                                autocomplete="username"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Enter your email"
                            >
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="current-password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Enter your password"
                            >
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <label for="remember_me" class="flex items-center">
                                <input 
                                    id="remember_me" 
                                    type="checkbox" 
                                    name="remember"
                                    class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500"
                                >
                                <span class="ml-2 text-sm text-gray-600">Remember me</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>

                        <button 
                            type="submit"
                            class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition"
                        >
                            Log in
                        </button>

                        <div class="mt-6 text-center">
                            <p class="text-sm text-gray-600">
                                Don't have an account? 
                                <a href="{{ route('register') }}" class="text-green-600 hover:text-green-700 font-semibold">
                                    Register here
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