<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'title' => 'required|string|between:5,50',
            'contents' => 'nullable|string',
            'labels' => 'nullable|distinct|exists:article_labels,id',
        ];

        switch ($this->route()->uri) {
            case 'api/admin/articles/create':
                return $rules;
                break;
            case 'api/admin/articles/edit':
                $rules['id'] = 'required|integer|min:0';
                return $rules;
                break;
        }
    }

    public function attributes()
    {
        return [
            'title' => '标题',
            'body' => '内容',
            'labels' => '标签',
        ];
    }
}
