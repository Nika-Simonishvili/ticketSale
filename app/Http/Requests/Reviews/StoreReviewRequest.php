<?php

namespace App\Http\Requests\Reviews;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => 'required|int|exists:events,id',
            'comment' => 'nullable|string',
            'rating' => 'required|int|min:1|max:5',
            'user_id' => 'nullable',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => \Auth::id(),
        ]);
    }
}
