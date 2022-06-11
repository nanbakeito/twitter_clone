<?php

namespace App\Http\Requests;

class TweetCreateRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if (!$this->image === 'null') {
            return [
                'text' => ['required', 'string', 'max:140'],
                'image' => ['file', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ];
        } else {
            return [
                'text' => ['required', 'string', 'max:140'],
            ];
        }
    }
}
