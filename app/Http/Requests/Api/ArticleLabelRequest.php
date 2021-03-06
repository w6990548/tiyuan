<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ArticleLabelRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|between:1,10|unique:article_labels,name'
        ];
    }

    public function attributes()
    {
        return [
            'name' => '标签',
        ];
    }
}
