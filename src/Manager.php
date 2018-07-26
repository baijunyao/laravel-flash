<?php

namespace Baijunyao\LaravelFlash;

use Baijunyao\LaravelPluginManager\Contracts\PluginManager;
use Illuminate\Support\Str;

class Manager extends PluginManager
{
    protected $element = 'body';

    protected function load()
    {
        $init = '';
        // 自定义提示信息
        if (session()->has('laravel-flash')) {
            foreach (session('laravel-flash') as $k => $v) {
                $init .= 'toastr.'.$v['alert-type'].'("'.$v['alert-message'].'");';
            }
            flash_clear();
        }
        // Validate 表单验证的错误信息
        if (session()->has('errors')) {
            foreach (session('errors')->all() as $k => $v) {
                $init .= 'toastr.error'.'("'.$v.'");';
            }
        }
        $toastrJs = <<<php
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
        $this->cssFile('statics/toastr-2.1.1/toastr.min.css')
            ->jquery()
            ->jsFile('statics/toastr-2.1.1/toastr.min.js')
            ->jsContent($toastrJs);
    }

    /**
     * 判断是否有需要弹出提示的内容
     * 
     * @return bool
     */
    public function verify()
    {
        // 检查是否有提示内容
        if (!session()->has('laravel-flash') && !session()->has('errors')) {
            return false;
        }

        // 跳过排除的路由
        $path = request()->path();
        $except = config('flash.except');
        foreach ($except as $k => $v) {
            if (Str::is(trim($v, '/'), $path)) {
                return false;
            }
        }

        return true;

    }

}