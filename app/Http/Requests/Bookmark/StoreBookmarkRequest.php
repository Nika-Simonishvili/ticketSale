<?php

namespace App\Http\Requests\Bookmark;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookmarkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => 'required|int|exists:events,id',
        ];
    }
}
