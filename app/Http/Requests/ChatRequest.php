<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'assistant_id' => 'required|int|exists:assistants,id',
            'action_id' => 'required|int|exists:actions,id',
            'thread_id' => 'int|nullable|exists:threads,id',
            'input' => 'required|string',
        ];
    }
}
