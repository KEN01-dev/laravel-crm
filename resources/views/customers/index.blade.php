<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Customers</h2>

            <div class="flex gap-2">
                <a href="{{ route('customers.export', request()->query()) }}" class="px-4 py-2 border rounded">CSV</a>
                <a href="{{ route('customers.create') }}" class="px-4 py-2 bg-gray-900 text-white rounded">New</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form class="flex gap-3 mb-4" method="GET" action="{{ route('customers.index') }}">
                    <input name="keyword" value="{{ request('keyword') }}" class="border rounded px-3 py-2 w-full" placeholder="Search name/company/email/phone">
                    <select name="status" class="border rounded px-3 py-2">
                        <option value="">All</option>
                        @foreach (['active'=>'active','prospect'=>'prospect','inactive'=>'inactive'] as $k => $label)
                            <option value="{{ $k }}" @selected(request('status')===$k)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="px-4 py-2 bg-gray-800 text-white rounded">Filter</button>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b">
                                <th class="py-2">Name</th>
                                <th class="py-2">Company</th>
                                <th class="py-2">Status</th>
                                <th class="py-2">Updated</th>
                                <th class="py-2 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $c)
                                <tr class="border-b">
                                    <td class="py-2 font-medium">{{ $c->name }}</td>
                                    <td class="py-2">{{ $c->company }}</td>
                                    <td class="py-2">{{ $c->status }}</td>
                                    <td class="py-2">{{ $c->updated_at->format('Y-m-d') }}</td>

                                    <td class="py-2 text-right space-x-3">
                                        <a class="underline" href="{{ route('customers.show', $c) }}">View</a>

                                        @can('update', $c)
                                            <a class="underline" href="{{ route('customers.edit', $c) }}">Edit</a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="py-4" colspan="5">No customers.</td></tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
                <div class="mt-4">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
