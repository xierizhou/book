<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookCollectionLog extends Model
{
    protected $fillable = [
        'book_id','from_url'
    ];
}
