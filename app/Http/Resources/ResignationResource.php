<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResignationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'resignation_date'=>$this->resignation_date,
            'reason'=>$this->when(!empty($this->reason), $this->reason),
            'status'=>$this->when(!empty($this->status), $this->status),
        ];
    }
}
