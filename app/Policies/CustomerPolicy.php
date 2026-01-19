<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Customer $customer): bool
    {
        return $user->role === 'admin' || $customer->owner_user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Customer $customer): bool
    {
        return $user->role === 'admin' || $customer->owner_user_id === $user->id;
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->role === 'admin' || $customer->owner_user_id === $user->id;
    }
}
