<?php
/**
 * Created by PhpStorm.
 * User: elite
 * Date: 2018/10/8
 * Time: 17:16
 */

namespace App\Tools\Collectors\Rules;


use App\Tools\Collectors\BaseRule;
use App\Models\Book;
use App\Models\Category;
use App\Models\BookChapterCollection;
class AishulaRule extends BaseRule
{
    private $url;
    public function __construct($url)
    {
        $this->url = $url;
    }

    public function url()
    {
        return $this->url;
    }

    public function book_list(){

        $table = $this->table();
        if(!$table){
            return false;
        }
        $desc = $this->desc(); //描述

        $title = $this->title($table); //获取标题

        $cate = $this->cate($table);  //类别

        $author = $this->author($table); //作者

        $update_time = $this->update_time($table); //更新时间

        $chapter_url = $this->chapter_url($table); //正文url

        $cover = $this->cover($table); //获取封面

        if(!$categories = Category::where("name",$cate)->first()){
            $categories = Category::create([
                'name'=>$cate,
            ]);
        }
        if(!$book = Book::where("title",$title)->where('author',$author)->first()){
            $book = Book::create([
                'title'=>$title,
                'categories_id'=>$categories->id,
                'author'=>$author,
                'desc'=>$desc,
                'cover'=>$cover,
                'chapter_url'=>$chapter_url,
                'last_update_at'=>strtotime($update_time),
                'from_url'=>$this->url(),

            ]);
            BookChapterCollection::insert([
                'book_id'=>$book->id,
                'chapter_number'=>0,
            ]);
        }

        return $book;
    }

    public function desc(){
        $str=preg_replace("/\s+/", " ", $this->content);
        $rule = '/<div id="CrbsSum">【作品简介】：(.*)<\/div>/isU';
        preg_match($rule,$str,$match);
        return $match[1];
    }

    public function table(){
        $str=preg_replace("/\s+/", " ", $this->content); //过滤多余回车

        $rule = '/<table width="100%" border="0">(.*)<\/table>/isU';

        preg_match_all($rule,$str,$match);

        if(!$match[1]){
            return false;
        }
        return $match[1][0];
    }

    public function cate($val){

        $rule = '/<td width="16%" height="20" align="center" bgcolor="#FFFFFF">(.*)<\/td>/isU';
        preg_match($rule,$val,$match);
        return $match[1];
    }

    public function title($val)
    {
        $rule = '/<h1 style="font-size:22px;line-height:120%;padding:0px;margin:0px;padding-left:50px">《(.*?)》<\/h1>/ies';
        preg_match($rule,$val,$match);
        return $match[1];
    }




    public function new_cha($val,$key){
        //$rule = '/<td style="padding-left:10px"><a href="(.*?)" target="_blank">(.*?)<\/a><\/td>/ies';
        $rule = '/<td style="padding-left:10px"><a href="(.*?)" target="_blank">(.*?)<\/a><\/td>/ies';
        preg_match($rule,$val,$match);


        return $match[2];
    }

    public function author($val){

        $rule = '/class="green_12" target="_blank">(.*?)<\/font>/ies';
        preg_match($rule,$val,$match);
        return $match[1];
    }

    public function update_time($val){
        $rule = '/<td width="27%" height="20" align="center" bgcolor="#FFFFFF">(.*?)<\/td>/ies';
        preg_match($rule,$val,$match);
        return $match[1];
    }



    public function chapter_url($val){
        $rule = '/【<a href="(.*?)" ><font color="#CC0000">点击阅读<\/font><\/a>/isU';
        preg_match($rule,$val,$match);
        return $match[1];
    }

    public function cover($val){
        $rule = '/<img src="(.*?)" width="120" height="150" class="picborder" \/>/ies';
        preg_match($rule,$val,$match);
        return $this->crabImage($match[1],public_path('uploads/images/'));

    }

    /**
     * PHP将网页上的图片攫取到本地存储
     * @param $imgUrl  图片url地址
     * @param string $saveDir 本地存储路径 默认存储在当前路径
     * @param null $fileName 图片存储到本地的文件名
     * @return mix
     */
    function crabImage($imgUrl, $saveDir='./', $fileName=null){

        if(empty($imgUrl)){
            return false;
        }

        //获取图片信息大小
        $imgSize = getImageSize($imgUrl);
        if(!in_array($imgSize['mime'],array('image/jpg', 'image/gif', 'image/png', 'image/jpeg'),true)){
            return false;
        }

        //获取后缀名
        $_mime = explode('/', $imgSize['mime']);
        $_ext = '.'.end($_mime);

        if(empty($fileName)){  //生成唯一的文件名
            $fileName = uniqid(time()).$_ext;
        }

        //开始攫取
        ob_start();
        readfile($imgUrl);
        $imgInfo = ob_get_contents();
        ob_end_clean();

        if(!file_exists($saveDir)){
            mkdir($saveDir,0777,true);
        }
        $fp = fopen($saveDir.$fileName, 'a');
        $imgLen = strlen($imgInfo);    //计算图片源码大小
        $_inx = 1024;   //每次写入1k
        $_time = ceil($imgLen/$_inx);
        for($i=0; $i<$_time; $i++){
            fwrite($fp,substr($imgInfo, $i*$_inx, $_inx));
        }

        return '/uploads/images/'.$fileName;
        //return array('file_name'=>$fileName,'save_path'=>$saveDir.$fileName);
    }




}