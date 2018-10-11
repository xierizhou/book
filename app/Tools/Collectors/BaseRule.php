<?php
/**
 * Created by PhpStorm.
 * User: elite
 * Date: 2018/10/8
 * Time: 17:12
 */

namespace App\Tools\Collectors;


abstract class BaseRule
{
    protected $title_rule = "";

    protected $content;

    abstract public function url();

    public function request(){

        $data =file_get_contents($this->url());
        $this->content = mb_convert_encoding($data, 'UTF-8','GBK' );
        return $this;
    }

    /**
     * 自定义的异常处理函数
     * @param $errno int
     * @param $errstr string
     * @param $errfile string
     * @param $errline int
     * @throws \Exception
     */
    function customError($errno, $errstr, $errfile, $errline)
    {
        $res = PHP_EOL . " 错误代码: [${errno}] ${errstr}" . PHP_EOL;
        $res .= " 错误所在的代码行: {$errline}".PHP_EOL;
        $res .= " 文件: {$errfile}" . PHP_EOL;
        // 自定义错误处理时，手动抛出异常
        throw new \Exception($res);
        // echo $res;
    }

    /**
     * 捕获致命错误
     * @throws
     */
    function FetalError()
    {
        $err_info = error_get_last();
        if ($err_info) {
            $this->customError($err_info['type'], $err_info['message'], $err_info['file'], $err_info['line']);
        }
    }

}