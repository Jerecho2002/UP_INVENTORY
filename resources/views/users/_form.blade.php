<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Name</label>
    <input type="text" name="name" value="{{ old('name', optional($user ?? null)->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Email</label>
    <input type="email" name="email" value="{{ old('email', optional($user ?? null)->email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    @error('email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
@if (empty($user?->id))
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Password</label>
    <input type="password" name="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    @error('password') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
    <input type="password" name="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
</div>
@endif
