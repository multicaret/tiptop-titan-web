<?php

namespace App\Http\Resources;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Notification */
class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'type' => $this->type(),
            //'user' => new UserResource($this->user),
            'body' => $this->data['body'],
            'orderCode' => $this->data['object_title'],
            'image' => $this->data['image'] ?? null,
//            'subjectID' => $this->data['subject_id'] ?? null,
//            'objectID' => $this->data['object_id'] ?? null,
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
