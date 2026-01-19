<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CustomerController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $user = $request->user();

        $q = Customer::query();

        if ($user->role !== 'admin') {
            $q->where('owner_user_id', $user->id);
        }

        if ($request->filled('keyword')) {
            $kw = $request->string('keyword')->toString();
            $q->where(function ($sub) use ($kw) {
                $sub->where('name', 'like', "%{$kw}%")
                    ->orWhere('company', 'like', "%{$kw}%")
                    ->orWhere('email', 'like', "%{$kw}%")
                    ->orWhere('phone', 'like', "%{$kw}%");
            });
        }

        if ($request->filled('status')) {
            $q->where('status', $request->string('status')->toString());
        }

        $customers = $q->latest()->paginate(15)->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function export(Request $request)
    {
        $user = $request->user();

        $q = Customer::query();

        if ($user->role !== 'admin') {
            $q->where('owner_user_id', $user->id);
        }

        if ($request->filled('keyword')) {
            $kw = $request->string('keyword')->toString();
            $q->where(function ($sub) use ($kw) {
                $sub->where('name', 'like', "%{$kw}%")
                    ->orWhere('company', 'like', "%{$kw}%")
                    ->orWhere('email', 'like', "%{$kw}%")
                    ->orWhere('phone', 'like', "%{$kw}%");
            });
        }

        if ($request->filled('status')) {
            $q->where('status', $request->string('status')->toString());
        }

        $filename = 'customers_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $columns = [
            'id',
            'name',
            'company',
            'email',
            'phone',
            'status',
            'owner_user_id',
            'created_at',
            'updated_at',
        ];

        return response()->streamDownload(function () use ($q, $columns) {
            $out = fopen('php://output', 'w');

            fwrite($out, "\xEF\xBB\xBF"); // Excel向けBOM

            fputcsv($out, $columns);

            $q->orderBy('id')->chunk(500, function ($rows) use ($out, $columns) {
                foreach ($rows as $c) {
                    $line = [];
                    foreach ($columns as $col) {
                        $line[] = data_get($c, $col);
                    }
                    fputcsv($out, $line);
                }
            });

            fclose($out);
        }, $filename, $headers);
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $owners = $user->role === 'admin'
            ? User::query()->orderBy('name')->get(['id', 'name', 'email'])
            : collect();

        return view('customers.create', compact('owners'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:active,prospect,inactive'],
            'notes' => ['nullable', 'string'],
            'owner_user_id' => ['nullable', 'exists:users,id'],
        ]);

        $ownerId = $user->role === 'admin'
            ? (int)($validated['owner_user_id'] ?? $user->id)
            : $user->id;

        $customer = Customer::create([
            'owner_user_id' => $ownerId,
            'name' => $validated['name'],
            'company' => $validated['company'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('customers.show', $customer);
    }

    public function show(Request $request, Customer $customer)
    {
        $this->authorize('view', $customer);

        return view('customers.show', compact('customer'));
    }

    public function edit(Request $request, Customer $customer)
    {
        $this->authorize('update', $customer);

        $user = $request->user();
        $owners = $user->role === 'admin'
            ? User::query()->orderBy('name')->get(['id', 'name', 'email'])
            : collect();

        return view('customers.edit', compact('customer', 'owners'));
    }

    public function update(Request $request, Customer $customer)
    {
        $this->authorize('update', $customer);

        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:active,prospect,inactive'],
            'notes' => ['nullable', 'string'],
            'owner_user_id' => ['nullable', 'exists:users,id'],
        ]);

        if ($user->role === 'admin' && isset($validated['owner_user_id'])) {
            $customer->owner_user_id = (int)$validated['owner_user_id'];
        }

        $customer->fill([
            'name' => $validated['name'],
            'company' => $validated['company'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ])->save();

        return redirect()->route('customers.show', $customer);
    }

    public function destroy(Request $request, Customer $customer)
    {
        $this->authorize('delete', $customer);

        $customer->delete();

        return redirect()->route('customers.index');
    }
}
