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
            'ID' => $this->identity_number,
            'Name' => $this->name,
            'Email' => $this->email,
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
