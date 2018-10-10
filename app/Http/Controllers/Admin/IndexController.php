<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tools\Collectors\Rules\lewen123\LewenRule;
class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
    //
    public function index()
    {
        $LewenRule = new LewenRule("http://www.lewen123.com/lewen/4.html");
        dd($LewenRule->request()->get());

    }
}
