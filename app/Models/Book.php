<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    const STATIC_SER = 1;  //连载中
    const STATIC_END = 2;   //完结

    protected $fillable = [
        'categories_id','title','author','new_chapter','desc','status','reading_volume','collect_volume','cover','sort','last_update_at','chapter_url','from_url','txt_count'
    ];

    /**
     * 获取分类
     */
    public function cate()
    {
        return $this->belongsTo(Category::class,'categories_id');
    }

}
