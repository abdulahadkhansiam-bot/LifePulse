@extends('layouts.app', ['title' => 'User Profile - LifePulse'])

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Account Profile</h1>
            <p class="text-xs text-slate-400 mt-0.5">Manage your personal information and security credentials</p>
        </div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-xs">
            <span class="text-slate-400">Account Role:</span>
            <span class="px-2 py-0.5 rounded-full bg-red-600/10 border border-red-500/20 text-red-400 font-bold uppercase text-[10px]">
                {{ $user->role }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Left: Summary Card -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 h-fit text-center">
            <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-red-600 to-rose-400 text-white font-extrabold text-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-red-600/20">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h2 class="text-lg font-bold text-white">{{ $user->name }}</h2>
            <p class="text-xs text-slate-400">{{ $user->email }}</p>

            <div class="mt-6 pt-5 border-t border-slate-800/80 space-y-3 text-left text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">Blood Group</span>
                    <span class="font-bold text-red-400">{{ $user->blood_group ?? 'Not set' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">Account Status</span>
                    <span class="inline-flex items-center gap-1.5 font-semibold text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">Member Since</span>
                    <span class="text-slate-300 font-medium">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Right: Forms Column -->
        <div class="md:col-span-2 space-y-6">
            <!-- Update Personal Details Form -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
                <h3 class="text-base font-bold text-white mb-1">Personal Details</h3>
                <p class="text-xs text-slate-400 mb-5">Update your contact information and location</p>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Full Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name', $user->name) }}" 
                            required 
                            class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border @error('name') border-red-500 @else border-slate-700 @enderror text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                        >
                        @error('name')
                            <p class="text-xs text-red-400 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Phone Number</label>
                            <input 
                                type="text" 
                                name="phone" 
                                id="phone" 
                                value="{{ old('phone', $user->phone) }}" 
                                class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border border-slate-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                            >
                            @error('phone')
                                <p class="text-xs text-red-400 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="blood_group" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Blood Group</label>
                            <select 
                                name="blood_group" 
                                id="blood_group" 
                                class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border border-slate-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                            >
                                <option value="">-- Not specified --</option>
                                @foreach ($bloodGroups as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group', $user->blood_group) === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="city" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">City / Region</label>
                            <input 
                                type="text" 
                                name="city" 
                                id="city" 
                                value="{{ old('city', $user->city) }}" 
                                class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border border-slate-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                            >
                        </div>

                        <div>
                            <label for="address" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Address / Landmark</label>
                            <input 
                                type="text" 
                                name="address" 
                                id="address" 
                                value="{{ old('address', $user->address) }}" 
                                class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border border-slate-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                            >
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white font-semibold text-xs uppercase tracking-wider rounded-lg shadow-sm transition"
                    >
                        Save Details
                    </button>
                </form>
            </div>

            <!-- Change Password Form -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
                <h3 class="text-base font-bold text-white mb-1">Security & Password</h3>
                <p class="text-xs text-slate-400 mb-5">Ensure your account is protected with a strong, distinct password</p>

                <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Current Password</label>
                        <input 
                            type="password" 
                            name="current_password" 
                            id="current_password" 
                            required 
                            class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border @error('current_password') border-red-500 @else border-slate-700 @enderror text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                        >
                        @error('current_password')
                            <p class="text-xs text-red-400 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">New Password</label>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                required 
                                placeholder="Min. 8 characters"
                                class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border @error('password') border-red-500 @else border-slate-700 @enderror text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                            >
                            @error('password')
                                <p class="text-xs text-red-400 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Confirm New Password</label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation" 
                                required 
                                placeholder="Re-type new password"
                                class="w-full px-3.5 py-2.5 rounded-lg bg-slate-950 border border-slate-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                            >
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs uppercase tracking-wider rounded-lg border border-slate-700 shadow-sm transition"
                    >
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
