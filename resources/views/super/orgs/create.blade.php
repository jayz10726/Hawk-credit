@extends('layouts.app')
@section('title','New Organization')
@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-xl font-bold text-gray-100 mb-6">Create Organization</h1>
    <div class="bg-gray-900 rounded-xl border border-gray-700 shadow-sm p-6">
        <form method="POST" action="{{ route('super.orgs.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Organization Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 border bg-gray-800 border-gray-600 text-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 border bg-gray-800 border-gray-600 text-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full px-4 py-2.5 border bg-gray-800 border-gray-600 text-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Address</label>
                    <textarea name="address" rows="2"
                              class="w-full px-4 py-2.5 border bg-gray-800 border-gray-600 text-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none">{{ old('address') }}</textarea>
                </div>
        <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Subscription Tier *</label>
                        <select name="subscription_tier" required
                                class="w-full px-4 py-2.5 border bg-gray-800 border-gray-600 text-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="basic">Basic</option>
                            <option value="professional">Professional</option>
                            <option value="enterprise">Enterprise</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Credit Pool (KES) *</label>
                        <input type="number" name="credit_pool" value="{{ old('credit_pool','0') }}" min="0" required
                               class="w-full px-4 py-2.5 border bg-gray-800 border-gray-600 text-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        Create Organization
                    </button>
                    <a href="{{ route('super.orgs.index') }}"
                       class="px-6 py-2.5 border border-gray-6000 text-gray-300 text-sm rounded-lg hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
