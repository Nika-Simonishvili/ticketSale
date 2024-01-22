<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'start_date_time' => 'required|date|date_format:Y-m-d H:i',
            'address' => 'required|string',
            'tickets_quantity' => 'required|int',
            'event_category_id' => 'required|integer|exists:event_categories,id',
        ];
    }
}
