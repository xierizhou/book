<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookChapterCollection extends Model
{
    protected $fillable = [
        'book_id','chapter_number'
    ];
}
