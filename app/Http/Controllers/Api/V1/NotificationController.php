<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends BaseApiController
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications()
                              ->whereNull('read_at')
                              ->latest()
                              ->get();

        return NotificationResource::collection($notifications)->additional([
            'meta' => [
                'unreadNotificationsCount' => $user->unreadNotifications->count()
            ]
        ]);
    }

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request)
    {
        $user = $request->user();

        if ($request->has('id')) {
            $user->unreadNotifications()
                 ->whereId($request->id)
                 ->update(['read_at' => now()]);
        } else {
            // make all as read
            $user->unreadNotifications->markAsRead();
        }

        return response([
            'count' => $user->unreadNotifications()->count()
        ]);
    }
}
