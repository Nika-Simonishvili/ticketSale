<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'start_date_time' => 'required|date|date_format:Y-m-d H:i|after:now',
            'address' => 'required|string',
            'tickets_quantity' => 'required|int',
            'available_tickets_quantity' => 'nullable',
            'event_category_id' => 'required|integer|exists:event_categories,id',
        ];
    }

    public function prepareForValidation()
    {
        return $this->merge([
            'available_tickets_quantity' => $this->tickets_quantity,
        ]);
    }
}
