<?php
/**
 * Created by PhpStorm.
 * User: elite
 * Date: 2018/10/10
 * Time: 14:03
 */

namespace App\Tools\Collectors\Rules\lewen123;


use App\Tools\Collectors\BaseRule;
use App\Models\Book;
use App\Models\Category;
use App\Models\BookChapterCollection;
use App\Models\BookCollectionLog;
use App\Models\BookCollectionErrorLog;
use Illuminate\Support\Facades\DB;
class LewenRule extends BaseRule
{
    private $url;
    public function __construct($url)
    {
        $this->url = $url;

        ini_set('display_errors', false);
        // 使用自定义的异常处理函数来替代PHP的错误处理
        set_error_handler(array($this,'customError'));
        //set_exception_handler('handleException');
        // 当PHP终止的时候（执行完成或者是遇到致命错误中止的时候）会调用FetalError方法
        register_shutdown_function(array($this,'FetalError'));
    }

    public function url()
    {
        return $this->url;
    }

    public function get(){

        list($title,$author) = $this->title();
        $desc = $this->desc();
        $txt_count = $this->txt_count();
        $update_time = $this->update_time();
        $chapter_url = $this->chapter_url();
        $cover = $this->cover();
        $cate = $this->cate();

        DB::beginTransaction();
        try{
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
                    'txt_count'=>$txt_count,

                ]);
                BookChapterCollection::insert([
                    'book_id'=>$book->id,
                    'chapter_number'=>0,
                ]);
                BookCollectionLog::create([
                    'book_id'=>$book->id,
                    'from_url'=>$this->url(),
                    'is_succeed'=>1,
                    'content'=>$this->content
                ]);

            }
            DB::commit();
            return $book;
        } catch (\Exception $e){
            DB::rollback();//事务回滚'

            BookCollectionErrorLog::create([
                'from_url'=>$this->url(),
                'data'=>$this->content,
                'error'=>$e->getMessage(),
            ]);
        }


    }



    public function title(){
        $rule = '/<h1 class="f20h">(.*?)<em>作者：(.*?)<\/em><\/h1>/ies';
        preg_match($rule,$this->content,$match);
        return [array_get($match,1),array_get($match,2)];
    }

    public function desc(){
        $rule = '/<div class="intro" style="text-indent:2em;"><!--内容简介-->(.*?)<!--内容简介结束--><\/div>/ies';
        preg_match($rule,$this->content,$match);
        return array_get($match,1);
    }

    public function txt_count(){
        $rule = '/<td><b>全文字数：<\/b>(.*?)字<\/td>/ies';
        preg_match($rule,$this->content,$match);
        return array_get($match,1);
    }

    public function update_time(){
        $rule = '/<td><b>更新时间：<\/b>(.*?)<\/td>/ies';
        preg_match($rule,$this->content,$match);
    }

    public function chapter_url(){
        $rule = '/<span class="btopt"><a href="(.*?)"/ies';
        preg_match($rule,$this->content,$match);
        return 'http://www.lewen123.com'.array_get($match,1);
    }

    public function cover(){
        $rule = '/<img src="(.*?)" alt="(.*?)" width="120" height="150">/ies';
        preg_match($rule,$this->content,$match);
        return $this->crabImage(array_get($match,1),public_path('uploads/images/'));
    }

    public function cate(){
        $rule = '/<td width="24%"><b>小说分类：<\/b>(.*?)<\/td>/ies';
        preg_match($rule,$this->content,$match);
        return array_get($match,1);

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