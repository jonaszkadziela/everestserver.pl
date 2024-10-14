<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserDeleted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly User $user)
    {
    }
}
