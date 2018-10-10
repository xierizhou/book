<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tools\Collectors\Rules\lewen123\LewenRule;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        for($i=2054;$i<=10000;$i++){
            $LewenRule = new LewenRule("http://www.lewen123.com/lewen/$i.html");
            $LewenRule->request()->get();
            sleep(1);
        }

        dd("ok");


    }
}
