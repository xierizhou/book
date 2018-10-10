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
        $this->content = iconv("gbk","UTF-8",$data);
        return $this;
    }



}