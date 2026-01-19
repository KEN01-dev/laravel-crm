<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $customer->name }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('customers.edit', $customer) }}" class="px-4 py-2 border rounded">Edit</a>
                <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Delete?')">
                    @csrf
                    @method('DELETE')
                    <button class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6 space-y-3">
                <div><span class="font-semibold">Company:</span> {{ $customer->company }}</div>
                <div><span class="font-semibold">Email:</span> {{ $customer->email }}</div>
                <div><span class="font-semibold">Phone:</span> {{ $customer->phone }}</div>
                <div><span class="font-semibold">Status:</span> {{ $customer->status }}</div>
                <div><span class="font-semibold">Notes:</span><br>{{ $customer->notes }}</div>

                <div class="pt-4">
                    <a class="underline" href="{{ route('customers.index') }}">Back to list</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
