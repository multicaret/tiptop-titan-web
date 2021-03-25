<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Notification */
class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => (int) $this->id,
            'type' => $this->type(),
//            'user' => new UserResource($this->user),
            //'data' => $this->data,
            'subjectID' => $this->data['subject_id'] ?? null,
            'objectID' => $this->data['object_id'] ?? null,
            'avatar' => $this->data['avatar'] ?? null,
            'userName' => $this->data['userName'] ?? null,
            'isUnread' => $this->unread(),
            'createdAt' => [
                'formatted' => $this->created_at->format(config('defaults.date.short_format')),
                'diffForHumans' => $this->created_at->diffForHumans(),
                'timestamp' => $this->created_at->timestamp,
            ],
        ];
    }

    public function type()
    {
        return last(explode('\\', $this->type));
    }
}
