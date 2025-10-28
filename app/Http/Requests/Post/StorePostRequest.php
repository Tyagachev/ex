<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'from' => 'required|string',
            'blocks' => 'required|array|min:1|max:50',
            'charCount' => 'string|max:5000',

            'blocks.*.type' => 'required|string|in:text,image',
            'blocks.*.content' => 'nullable|string',
            'blocks.*.file' => 'nullable|file|image|max:10120' // до 5 МБ
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Поле обязательно для заполнения',
            'title.max' => 'Максимальная длина заголовка — 255 символов',
            'blocks.required' => 'Нужно добавить хотя бы один блок',
            'blocks.min' => 'Пост должен содержать хотя бы один блок',
            'blocks.max' => 'Максимум 50 блоков',
            'blocks.*.file.max' => 'Изображение не должно превышать 5 МБ',
        ];
    }
}
