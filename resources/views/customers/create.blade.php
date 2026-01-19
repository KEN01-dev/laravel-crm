<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Customer</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form method="POST" action="{{ route('customers.store') }}" class="space-y-4">
                    @csrf

                    @if ($owners->count())
                        <div>
                            <label class="block text-sm mb-1">Owner</label>
                            <select name="owner_user_id" class="border rounded px-3 py-2 w-full">
                                @foreach ($owners as $o)
                                    <option value="{{ $o->id }}">{{ $o->name }} ({{ $o->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm mb-1">Name</label>
                        <input name="name" value="{{ old('name') }}" class="border rounded px-3 py-2 w-full" required>
                        @error('name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Company</label>
                        <input name="company" value="{{ old('company') }}" class="border rounded px-3 py-2 w-full">
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Email</label>
                        <input name="email" value="{{ old('email') }}" class="border rounded px-3 py-2 w-full">
                        @error('email') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Phone</label>
                        <input name="phone" value="{{ old('phone') }}" class="border rounded px-3 py-2 w-full">
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Status</label>
                        <select name="status" class="border rounded px-3 py-2 w-full">
                            @foreach (['active','prospect','inactive'] as $s)
                                <option value="{{ $s }}" @selected(old('status','active')===$s)>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Notes</label>
                        <textarea name="notes" class="border rounded px-3 py-2 w-full" rows="4">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-gray-900 text-white rounded">Create</button>
                        <a href="{{ route('customers.index') }}" class="px-4 py-2 border rounded">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
