<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function rules()
    {
        // 有ID则为编辑
        switch ($this->get('id')) {
            case 0:
                return [
                    'title' => 'required|string',
                    'contents' => 'nullable|string',
                    'labels' => 'nullable|distinct|exists:article_labels,id',
                ];
                break;
            default:
                return [
                    'title' => 'string',
                    'contents' => 'nullable|string',
                    'labels' => 'nullable|distinct|exists:article_labels,id',
                ];
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
