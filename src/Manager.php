<?php

namespace Baijunyao\LaravelFlash;

use Baijunyao\LaravelPluginManager\Contracts\PluginManager;

class Manager extends PluginManager
{
    protected $element = 'body';

    protected function load()
    {
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
        if (session()->has('alert-message') || session()->has('errors')) {
            return true;
        } else {
            return false;
        }
    }

}