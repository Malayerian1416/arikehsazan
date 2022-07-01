<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::channel('invoice_automation.{role_id}', function ($user,$role_id) {
        return (int) $user->role->id === (int) $role_id;
});
Broadcast::channel('worker_automation.{role_id}', function ($user,$role_id) {
    return (int) $user->role->id === (int) $role_id;
});
Broadcast::channel('leave_automation.{role_id}', function ($user,$role_id) {
    return (int) $user->role->id === (int) $role_id;
});
