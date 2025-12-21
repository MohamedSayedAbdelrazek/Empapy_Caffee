<?php

namespace App\Observers;

use App\Models\AdminNotification;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Only notify for customer registrations
        if ($user->role === 'customer') {
            AdminNotification::createNewCustomerNotification($user);
        }
    }
}
