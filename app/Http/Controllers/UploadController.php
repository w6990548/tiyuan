<?php

namespace App\Http\Controllers;

use App\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * 图片上传
     * @author: FengLei
     * @time: 2020/7/9 15:24
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function image(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,bmp,png,gif'
        ], [
            'file.required' => '图片不能为空',
            'file.mimes' => '图片格式不正确，必须为 jpeg, bmp, png, gif 类型。'
        ]);
        $file = $request->file('file');
        $path = 'CONFIG-IMG-'.$file->getInode();

        if ($file->getSize() > 2 * 1024 * 1024) {
            return Result::error(123, '图片大小不能超过2MB');
        }
        $disk = Storage::disk('qiniu');

        //将文件上传到七牛云，并且返回七牛云上相对路径
        $fileinfo = $disk->put($path, $file);

        //用相对路径来获取图片的完整路径
        $url = $disk->getUrl($fileinfo);

        return Result::success([
            'url' => $url,
            'size' => $file->getSize(),
        ]);
    }
}
