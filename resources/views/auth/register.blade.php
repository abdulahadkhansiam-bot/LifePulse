@extends('layouts.app', ['title' => 'Sign Up - LifePulse'])

@section('content')
<div class="max-w-2xl mx-auto px-4 py-6">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6 sm:p-8">
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-xl bg-red-600/10 border border-red-500/20 text-red-500 flex items-center justify-center mx-auto mb-3 shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Create LifePulse Account</h1>
            <p class="text-xs text-slate-400 mt-1">Select your role and enter your details to join the network</p>
        </div>

        <form method="POST" action="{{ route('register.store') }}" class="space-y-5" id="registerForm">
            @csrf

            <!-- Role Selection -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                    Select Your Role <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="donor" class="peer sr-only" {{ old('role', 'donor') === 'donor' ? 'checked' : '' }} onchange="toggleRoleFields(this.value)">
                        <div class="p-3.5 rounded-xl border border-slate-700 bg-slate-950/60 peer-checked:border-red-500 peer-checked:bg-red-500/10 transition text-center hover:border-slate-600">
                            <span class="block text-xs font-bold text-white peer-checked:text-red-400">Voluntary Donor</span>
                            <span class="block text-[11px] text-slate-400 mt-0.5">Pledge & donate blood</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="requester" class="peer sr-only" {{ old('role') === 'requester' ? 'checked' : '' }} onchange="toggleRoleFields(this.value)">
                        <div class="p-3.5 rounded-xl border border-slate-700 bg-slate-950/60 peer-checked:border-red-500 peer-checked:bg-red-500/10 transition text-center hover:border-slate-600">
                            <span class="block text-xs font-bold text-white peer-checked:text-red-400">Requester</span>
                            <span class="block text-[11px] text-slate-400 mt-0.5">Urgent patient needs</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="hospital" class="peer sr-only" {{ old('role') === 'hospital' ? 'checked' : '' }} onchange="toggleRoleFields(this.value)">
                        <div class="p-3.5 rounded-xl border border-slate-700 bg-slate-950/60 peer-checked:border-red-500 peer-checked:bg-red-500/10 transition text-center hover:border-slate-600">
                            <span class="block text-xs font-bold text-white peer-checked:text-red-400">Hospital Staff</span>
                            <span class="block text-[11px] text-slate-400 mt-0.5">Blood bank inventory</span>
                        </div>
                    </label>
                </div>
                @error('role')
                    <p class="text-xs text-red-400 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name') }}" 
                        required 
                        placeholder="e.g. Rifat Nuha"
                        class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border @error('name') border-red-500 @else border-slate-700 @enderror text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                    >
                    @error('name')
                        <p class="text-xs text-red-400 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}" 
                        required 
                        placeholder="e.g. user@example.com"
                        class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border @error('email') border-red-500 @else border-slate-700 @enderror text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                    >
                    @error('email')
                        <p class="text-xs text-red-400 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Phone Number -->
                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">
                        Phone Number
                    </label>
                    <input 
                        type="text" 
                        name="phone" 
                        id="phone" 
                        value="{{ old('phone') }}" 
                        placeholder="e.g. 01712345678"
                        class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border @error('phone') border-red-500 @else border-slate-700 @enderror text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                    >
                    @error('phone')
                        <p class="text-xs text-red-400 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Blood Group (Conditional required for Donor) -->
                <div id="bloodGroupWrapper">
                    <label for="blood_group" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">
                        Blood Group <span id="bloodGroupRequiredStar" class="text-red-500">*</span>
                    </label>
                    <select 
                        name="blood_group" 
                        id="blood_group" 
                        class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border @error('blood_group') border-red-500 @else border-slate-700 @enderror text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                    >
                        <option value="">-- Select Blood Group --</option>
                        @foreach ($bloodGroups as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                    @error('blood_group')
                        <p class="text-xs text-red-400 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- City -->
                <div>
                    <label for="city" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">City / Region</label>
                    <input 
                        type="text" 
                        name="city" 
                        id="city" 
                        value="{{ old('city') }}" 
                        placeholder="e.g. Dhaka, Chittagong"
                        class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border @error('city') border-red-500 @else border-slate-700 @enderror text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                    >
                    @error('city')
                        <p class="text-xs text-red-400 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Address / Landmark</label>
                    <input 
                        type="text" 
                        name="address" 
                        id="address" 
                        value="{{ old('address') }}" 
                        placeholder="e.g. Dhanmondi 27, House 12"
                        class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border @error('address') border-red-500 @else border-slate-700 @enderror text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                    >
                    @error('address')
                        <p class="text-xs text-red-400 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        autocomplete="new-password"
                        placeholder="Min. 8 characters"
                        class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border @error('password') border-red-500 @else border-slate-700 @enderror text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                    >
                    <span class="block text-[11px] text-slate-500 mt-1">Must have letters, numbers & symbols</span>
                    @error('password')
                        <p class="text-xs text-red-400 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="Re-type password"
                        class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border border-slate-700 text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                    >
                </div>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full py-3 px-4 bg-red-600 hover:bg-red-500 text-white font-semibold text-sm rounded-lg shadow-md shadow-red-600/30 transition duration-150 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-slate-900 mt-4"
            >
                Create Account
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-slate-800 text-center">
            <p class="text-xs text-slate-400">
                Already registered with LifePulse? 
                <a href="{{ route('login') }}" class="text-red-400 hover:text-red-300 font-semibold ml-1">Sign in here &rarr;</a>
            </p>
        </div>
    </div>
</div>

<script>
    function toggleRoleFields(role) {
        const bloodGroupRequiredStar = document.getElementById('bloodGroupRequiredStar');
        if (role === 'donor') {
            bloodGroupRequiredStar.style.display = 'inline';
        } else {
            bloodGroupRequiredStar.style.display = 'none';
        }
    }
</script>
@endsection
