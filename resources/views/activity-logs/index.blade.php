<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Activity Logs
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 検索フォーム --}}
            <form method="GET" class="bg-white p-4 mb-4 shadow rounded grid grid-cols-6 gap-4">
                <div>
                    <label class="text-sm">From</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="w-full border rounded">
                </div>

                <div>
                    <label class="text-sm">To</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="w-full border rounded">
                </div>

                <div>
                    <label class="text-sm">Action</label>
                    <select name="action" class="w-full border rounded">
                        <option value="">All</option>
                        <option value="created" @selected(request('action') === 'created')>created</option>
                        <option value="updated" @selected(request('action') === 'updated')>updated</option>
                        <option value="deleted" @selected(request('action') === 'deleted')>deleted</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm">User</label>
                    <input type="text" name="user" value="{{ request('user') }}"
                        placeholder="name / email"
                        class="w-full border rounded">
                </div>

                <div>
                    <label class="text-sm">Customer</label>
                    <input type="text" name="customer" value="{{ request('customer') }}"
                        placeholder="name / company"
                        class="w-full border rounded">
                </div>

                <div>
                    <label class="text-sm">IP</label>
                    <input type="text" name="ip" value="{{ request('ip') }}"
                        class="w-full border rounded">
                </div>

                <div class="col-span-6 flex gap-2 mt-2">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded">
                        Search
                    </button>
                    <a href="{{ route('activity-logs.index') }}"
                        class="px-4 py-2 bg-gray-300 rounded">
                        Reset
                    </a>
                </div>
            </form>

            {{-- 一覧 --}}
            <div class="bg-white shadow rounded">
                <table class="min-w-full border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">日時</th>
                            <th class="p-2 border">Action</th>
                            <th class="p-2 border">User</th>
                            <th class="p-2 border">Customer</th>
                            <th class="p-2 border">変更内容</th>
                            <th class="p-2 border">IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="border-t">
                                <td class="p-2 border">{{ $log->created_at }}</td>
                                <td class="p-2 border">{{ $log->action }}</td>
                                <td class="p-2 border">{{ $log->user->name ?? '-' }}</td>
                                <td class="p-2 border">{{ $log->customer->name ?? '-' }}</td>

                                {{-- updated の差分サマリ --}}
                                <td class="p-2 border text-sm text-gray-700">
                                    @if($log->action === 'updated')
                                        @php
                                            $before = $log->meta['before'] ?? [];
                                            $after  = $log->meta['after'] ?? [];

                                            $changedKeys = collect($after)->filter(
                                                fn ($v, $k) =>
                                                    array_key_exists($k, $before)
                                                    && $before[$k] !== $v
                                            )->keys();
                                        @endphp

                                        {{ $changedKeys->join(', ') ?: '-' }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="p-2 border">{{ $log->meta['ip'] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">
                                    No logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
