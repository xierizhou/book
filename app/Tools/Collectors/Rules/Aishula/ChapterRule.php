<?php
/**
 * Created by PhpStorm.
 * User: elite
 * Date: 2018/10/9
 * Time: 14:23
 */

namespace App\Tools\Collectors\Rules\Aishula;


use App\Tools\Collectors\BaseRule;
use App\Models\Chapter;
use App\Models\BookChapterCollection;
class ChapterRule extends BaseRule
{
    private $url;
    private $book_id;
    public function __construct($book_id,$url)
    {

        $this->url = $url;
        $this->book_id = $book_id;

    }

    public function url()
    {
        return $this->url;
    }

    public function run(){

        $str=preg_replace("/\s+/", " ", $this->content);
        $rule = '/<dl>(.*?)<\/dl>/ies';
        preg_match($rule,$str,$match);
        $rule = '/<dd>(.*?)<\/dd>/ies';
        preg_match_all($rule,$str,$match);

        //作用是接着更新
        $BookChapterCollection = BookChapterCollection::where("book_id",$this->book_id)->first();
        $data = array_slice($match[1],$BookChapterCollection->chapter_number); //从指定章节开始

        $chapter_numebr = 0;
        foreach($data as $key=>$val){
            $rule = '/<a href="(.*?)" alt="(.*?)">(.*?)<\/a>/ies';
            preg_match($rule,$val,$chat_match);
            if(!array_get($chat_match,3) || !array_get($chat_match,1)){
                continue;
            }
            $name = $chat_match[3];
            $url = $chat_match[1];
            $content = $this->getContent($url);
            $chapter_numebr = $key+1;
            $res = Chapter::create([
                'book_id'=>$this->book_id,
                'title'=>$name,
                'txt'=>$content,
                'sort'=>$chapter_numebr,
                'from_url'=>$this->url.$url,
            ]);
            if(!$res){
                break;
            }
			sleep(1);
        }
        BookChapterCollection::where("book_id",$this->book_id)->update(['chapter_number'=>$chapter_numebr]);

    }


    //获取该章节的内容
    public function getContent($url){
        $url = $this->url.$url;
        $content = file_get_contents($url);
        $content = iconv("gbk","UTF-8",$content);
        $str=preg_replace("/\s+/", " ", $content);
        $rule = '/<div id="booktext"><!--go-->(.*?)<!--over--><\/div>/ies';
        preg_match($rule,$content,$match);
        $content=preg_replace("/\s+/", " ", $match[1]);
        return $content;
    }




}