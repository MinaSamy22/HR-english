<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VacationResource extends JsonResource
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
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
            'total'=>$this->total,
            'vacation_type'=>$this->vacation_type,
            'reason'=>$this->when(!empty($this->reason), $this->reason),
            'status'=>$this->when(!empty($this->status), $this->status),
        ];
    }
}
