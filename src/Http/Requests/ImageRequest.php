<?php

namespace  KameCode\Image\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // 'images.*' => 'required|image|max:2048',
            'entity' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'images' => 'imagen'
        ];
    }

    public function messages()
    {
        return [
            'message.required' => 'Ocurrió un error inesperado',
        ];
    }
}
