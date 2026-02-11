<x-layouts.app>
    <div class="flex flex-1 items-center justify-center px-6 py-12 min-h-screen bg-gray-50">

        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

            <!-- Header -->
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Create an account') }}</h2>
                <p class="mt-2 text-sm text-gray-500">{{ __('Enter your details below to create your account') }}</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 text-center text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-4">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Full name"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm"
                    >
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email address') }}</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="email@example.com"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        placeholder="********"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm"
                    >
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('Confirm password') }}</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        placeholder="********"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm"
                    >
                </div>

                <!-- Submit Button -->
                <button type="submit" class="mt-4 w-full rounded-lg bg-orange-500 text-white py-2 px-4 font-medium hover:bg-orange-600 transition">
                    {{ __('Create account') }}
                </button>
            </form>

            <!-- Login Link -->
            <p class="mt-6 text-center text-sm text-gray-600">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}" class="text-orange-500 hover:underline font-medium">
                    {{ __('Log in') }}
                </a>
            </p>

        </div>
    </div>
</x-layouts.app>
