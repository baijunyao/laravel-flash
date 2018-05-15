<?php

namespace Baijunyao\LaravelFlash\Middleware;

use Closure;
use Illuminate\Support\Str;
use Baijunyao\LaravelMiddlewareManager\Manager;

class LaravelFlash
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $manager = new Manager($request, $next);
        if (!$manager->except(config('flash.except'))->verify()) {
            return $response;
        }

        // 判断是否有需要弹出提示的内容如果有则添加 toastr 否则直接返回
        if (!session()->has('alert-message') && !session()->has('errors')) {
            return $response;
        }

        $manager->cssFile('statics/toastr-2.1.1/toastr.min.css');

        // 插入js标签
        $toastrJsPath = asset('statics/toastr-2.1.1/toastr.min.js');
        $init = '';
        // 自定义提示信息
        if (session()->has('alert-message')) {
            $init = 'toastr.'.session('alert-type').'("'.session('alert-message').'");';
        }
        // Validate 表单验证的错误信息
        if (session()->has('errors')) {
            foreach (session('errors')->all() as $k => $v) {
                $init .= 'toastr.error'.'("'.$v.'");';
            }
        }
        $jqueryJsPath = asset('statics/jquery-2.2.4/jquery.min.js');

        $toastrJs = <<<php
<script>
    (function(){
        window.jQuery || document.write('<script src="$jqueryJsPath"><\/script>');
    })();
</script>
<script src="$toastrJsPath"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-center",
        "onclick": null,
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "progressBar": true
    }
    $init
</script>
php;
        $manager->jsContent($toastrJs);
        return $manager->response();
    }
}
