<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string',
            'contents' => 'nullable|string',
            'labels' => 'nullable|distinct|exists:article_labels,id',
        ];
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
