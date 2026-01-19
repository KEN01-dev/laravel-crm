<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class CustomerObserver
{
    public function created(Customer $customer): void
    {
        $this->log('created', $customer, null, $customer->toArray());
    }

    public function updated(Customer $customer): void
    {
        $dirty = $customer->getDirty();
        if (empty($dirty)) {
            return;
        }

        $before = [];
        $after = [];

        foreach (array_keys($dirty) as $key) {
            $before[$key] = $customer->getOriginal($key);
            $after[$key] = $customer->getAttribute($key);
        }

        $this->log('updated', $customer, $before, $after);
    }

    public function deleted(Customer $customer): void
    {
        $this->log('deleted', $customer, $customer->toArray(), null);
    }

    private function log(string $action, Customer $customer, ?array $before, ?array $after): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'target_type' => Customer::class,
            'target_id' => $customer->id,
            'meta' => [
                'before' => $before,
                'after' => $after,
                'ip' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ],
        ]);
    }
}
