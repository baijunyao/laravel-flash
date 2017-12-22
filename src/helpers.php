<?php

if (!function_exists('flash_success')){
    /**
     * 添加成功提示
     *
     * @param string $message
     */
    function flash_success($message = '成功')
    {
        session()->flash('alert-message', $message);
        session()->flash('alert-type', 'success');
    }
}

if (!function_exists('flash_error')){
    /**
     * 添加失败提示
     *
     * @param string $message
     */
    function flash_error($message = '失败')
    {
        session()->flash('alert-message', $message);
        session()->flash('alert-type', 'error');
    }
}