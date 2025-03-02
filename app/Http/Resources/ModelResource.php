<?php

namespace App\Http\Resources;

use App\Models\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Model $resource
 * @property int $id
 * @property string $name
 * @property string $connection_name
 */
class ModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->title,
            'type' => $this->resource->connection_name,
        ];
    }
}
