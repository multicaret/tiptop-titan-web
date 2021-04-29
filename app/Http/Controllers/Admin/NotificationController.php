<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{

    public function index()
    {
        return view('admin.notifications.index');
    }

    public function handle(DatabaseNotification $notification)
    {
        $notification->markAsRead();

        return redirect(route($notification->data['route']['name'],
            array_merge($notification->data['route']['variables'], $notification->data['route']['params'])));
    }

}
