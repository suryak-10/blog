<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreteBlogRequest extends FormRequest
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
            'blogTitle' => 'required|string|unique:posts,title',
            // 'slug' => 'required|unique:posts',
            'content' => 'required',
            'status' => 'required|in:draft,published,archived',
            'thumbnail' => 'nullable',
            "category_ids" => "required|array",
        ];
    }

    

    public function attributes(){
        return [
            "title" => "blog title",
        ];
    }
}
