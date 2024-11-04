<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class ProductUnitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => Hashids::encode($this->id),
            'ulid' => $this->ulid,
            'company' => new CompanyResource($this->company),
            'product' => new ProductResource($this->product),
            'unit' => new UnitResource($this->unit),
            'code' => $this->code,
            'is_base' => $this->is_base,
            'conversion_value' => $this->conversion_value,
            'is_primary_unit' => $this->is_primary_unit,
            'remarks' => $this->remarks,
        ];
    }
}
