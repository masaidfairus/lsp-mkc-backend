<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PSpell\Config\Errors;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public static $wrap = 'response';
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'identity' => [
                'type' => $this->identity_type,
                'number' => $this->identity_number,
            ],
            'role' => $this->role,
            'is_verified' => $this->email_verified_at !== null,
            'joined_at' => $this->created_at->format('d-M-Y'),
        ];
    }

    public function with(Request $request)
    {
        return [
            'metadata' => [
                'code' => 200,
                'message' => 'OK'
            ]
        ];
    }
}
