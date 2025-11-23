<x-guest-layout>
    <!-- Session Status -->
    <div class="row">
            <img src="{{ asset('storage/imcufide.png') }}" alt="Logo">
    </div>
        <br>
        <br>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email">Correo Electrónico:</label>

            <input id="email" type="email" :value="old('email')" name="email" class="form-control block mt-1 w-full" required autofocus autocomplete="username">

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password">Contraseña:</label>


            <input id="password" type="password" name="password" class="form-control block mt-1 w-full" required autocomplete="current-password">

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Recordarme</span>
            </label>
        </div>

        <br>
        <br>
        <div class="row">
            <button type="submit" class="btn btn-primary full-width">Iniciar Sesión</button>
        </div>
    </form>
</x-guest-layout>
