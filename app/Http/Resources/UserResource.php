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
    /**
     * To array.
     *
     * @param  Request  $request
     * @return array
     */
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
            'role' => 'asesi',
            'is_verified' => $this->email_verified_at !== null,
            'joined_at' => $this->created_at->format('d-M-Y'),
            'signature' => $this->userSignature ? $this->userSignature->signature_image : null,
        ];
    }

    /**
     * With.
     *
     * @param  Request  $request
     * @return mixed
     */
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
