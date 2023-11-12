<?php

namespace App\Http\Resources;

use App\Lib\Assistant\Actions\AssistantAction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $icon
 * @property string $model
 * @property string $shortcut
 * @property string $system_prompt
 * @property boolean $enabled
 */
class ActionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'icon' => $this->icon,
            'model' => $this->model,
            'shortcut' => $this->shortcut,
            'has_system_prompt' => isset($this->type::$systemPrompt),
            'system_prompt' => $this->system_prompt,
            'enabled' => $this->enabled,
            'custom' => $this->type === AssistantAction::class
        ];
    }
}
