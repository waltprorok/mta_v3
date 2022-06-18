<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'admin' => $this->admin,
            'teacher' => $this->teacher,
            'student' => $this->student,
            'parent' => $this->parent,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
