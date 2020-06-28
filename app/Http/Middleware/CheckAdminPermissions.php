<?php

namespace App\Http\Middleware;

use App\Exceptions\NoPermissionException;
use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * 验证用户是否有权限进行该操作
 * Class CheckAdminPermissions
 * @package App\Http\Middleware
 */
class CheckAdminPermissions
{
	/**
	 * 不需要验证权限的地址
	 * @var string[]
	 */
	private $apiUrl = [
		'api/admin/logout'
	];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	$user = Auth::guard('api')->user();
    	// 若添加权限的地址不是以 / 开头，补上，防止报错
	    if(substr($request->get('name'), 0, 1 ) !== "/"){
		    $request->offsetSet('name', '/'.$request->get('name'));
	    }
    	if (!in_array($request->path(), $this->apiUrl)) {
	        // 用户是否有站长权限
	        if (!$user->hasRole('zhanzhang')) {
	            // 用户是否有该权限
			    if (!$user->can($request->path())) {
				    throw new NoPermissionException('权限不足 '.$request->path());
			    }
		    }
	    }
        return $next($request);
    }
}
