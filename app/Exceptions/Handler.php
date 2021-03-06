<?php

namespace App\Exceptions;

use App\Result;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            return $this->renderForApi($request, $exception);
        } else {
            if ($request->is('admin/*')) {
                if ($exception instanceof NotFoundHttpException) {
                    return response()->view('admin');
                }
            }
        }
        return parent::render($request, $exception);
    }

    /**
     * 渲染api的异常
     * @param \Illuminate\Http\Request $request
     * @param Throwable $exception
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    private function renderForApi($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            $response = Result::error(10000, '接口不存在');
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $response = Result::error(500, '不允许的请求方法');
        } elseif ($exception instanceof ValidationException) {
            $errors = array_map(function (&$value) {
                return implode('|', $value);
            }, $exception->errors());
            $response = Result::error(10001, implode('|', $errors));
        } elseif ($exception instanceof AuthenticationException) {
            $response = Result::error(10003, '您尚未登录');
        } elseif ($exception instanceof PermissionAlreadyExists) {
            $response = Result::error(10004, '权限已存在');
        } elseif ($exception instanceof RoleAlreadyExists) {
	        $response = Result::error(10006, '角色已存在');
        } elseif ($exception instanceof ModelNotFoundException) {
	        $message = '数据不存在：'.$exception->getModel().'【'.implode(',', $exception->getIds()).'】';
	        $response = Result::error(10010, $message);
        } elseif ($exception instanceof ThrottleRequestsException) {
	        $response = Result::error(10010, '请求频繁，请稍后再试！');
        } else {
            $response = parent::render($request, $exception);
        }

        return $response;
    }
}
