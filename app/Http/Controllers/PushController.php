<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\PushNewInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PushController extends Controller
{
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request,[
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);
        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];
        $user = Auth::user();
        $user->updatePushSubscription($endpoint, $key, $token);

        return response()->json(['success' => true],200);
    }
    public function push(): \Illuminate\Http\RedirectResponse
    {
        Notification::send(User::query()->where("role_id","=",3)->get(),new PushNewInvoice);
        return redirect()->back();
    }
}
