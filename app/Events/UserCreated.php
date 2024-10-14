<?php

namespace App\Events;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Events\Dispatchable;

class UserCreated extends Registered
{
    use Dispatchable;
}
