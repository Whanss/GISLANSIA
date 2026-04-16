<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GIS Lansia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="flex w-[800px] shadow-lg rounded-xl overflow-hidden">

        {{-- Left Form --}}
        <div class="bg-white w-1/2 p-10 flex flex-col justify-center">
            <h2 class="text-2xl font-bold mb-1">Login</h2>
            <p class="text-gray-400 text-sm mb-6">Sign in to your account</p>

            @if($errors->any())
                <div class="bg-red-100 text-red-600 text-sm p-3 rounded mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf
                {{-- Email --}}
                <div class="mb-4 relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </span>
                    <input
                        type="email"
                        name="email"
                        placeholder="Email"
                        class="w-full border border-gray-300 rounded pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        required
                    >
                </div>

                {{-- Password --}}
                <div class="mb-6 relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input
                        type="password"
                        name="password"
                        placeholder="Password"
                        class="w-full border border-gray-300 rounded pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        required
                    >
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 text-sm">
                    Login
                </button>
            </form>
        </div>

        {{-- Right Panel --}}
        <div class="bg-indigo-600 w-1/2 flex items-center justify-center p-10">
            <p class="text-white text-center text-sm leading-relaxed">
                Sistem Informasi Geografis <br> Data Lansia <br> Kabupaten Lombok Tengah
            </p>
        </div>

    </div>
</body>
</html>
