<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookCollectionErrorLog extends Model
{
    protected $fillable = [
        'from_url','data'
    ];
}
