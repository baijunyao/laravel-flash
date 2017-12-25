<?php

namespace Baijunyao\LaravelFlash\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

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
        // 获取response内容
        $content = $response->getContent();
        // 判断是否有body标签如果有则添加 toastr
        if (false !== strripos($content, '</body>')) {
            // 插入css标签
            $toastrCssPath = asset('statics/toastr-2.1.1/toastr.min.css');

            $toastrCss = <<<php
<link href="$toastrCssPath" rel="stylesheet" type="text/css" />
</head>
php;

            // 插入js标签
            $toastrJsPath = asset('statics/toastr-2.1.1/toastr.min.js');
            $init = '';
            if (session()->has('alert-message')) {
                $init = 'toastr.'.session('alert-type').'("'.session('alert-message').'");';
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
</body>
php;

            $seach = [
                '</head>',
                '</body>'
            ];
            $subject = [
                $toastrCss,
                $toastrJs
            ];
            $content = str_replace($seach, $subject, $content);
        }

        // 更新内容并重置Content-Length
        $response->setContent($content);
        $response->headers->remove('Content-Length');
        return $response;
    }
}
