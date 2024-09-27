<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class ProductGroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => Hashids::encode($this->id),
            'ulid' => $this->ulid,
            'company' => new CompanyResource($this->company),
            'code' => $this->code,
            'name' => $this->name,
            'category' => $this->category,
        ];
    }
}
