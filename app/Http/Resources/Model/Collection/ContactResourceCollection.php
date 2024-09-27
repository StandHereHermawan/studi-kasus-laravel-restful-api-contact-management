<?php

namespace App\Http\Resources\Model\Collection;

use App\Http\Resources\Model\ContactResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ContactResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "data" => ContactResource::collection($this->collection),
        ];
    }
}
