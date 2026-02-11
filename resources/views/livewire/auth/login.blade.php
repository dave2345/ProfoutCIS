<x-layouts.app>
    <div class="flex flex-1 items-center justify-center px-6 py-12 bg-gray-50 min-h-screen">

        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

            <!-- Header -->
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Log in to your account') }}</h2>
                <p class="mt-2 text-sm text-gray-500">{{ __('Enter your email and password below to log in') }}</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 text-center text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email address') }}</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="email@example.com"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="********"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm"
                    >

                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="absolute top-0 right-0 text-sm text-orange-600 hover:underline mt-2">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mt-2">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-orange-600 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">{{ __('Remember me') }}</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="mt-4 w-full rounded-lg bg-orange-500 text-white py-2 px-4 font-medium hover:bg-orange-600 transition">
                    {{ __('Log in') }}
                </button>
            </form>

            <!-- Register Link -->
            @if (Route::has('register'))
                <p class="mt-6 text-center text-sm text-gray-600">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}" class="text-orange-500 hover:underline font-medium">
                        {{ __('Sign up') }}
                    </a>
                </p>
            @endif

        </div>

    </div>
</x-layouts.app>
