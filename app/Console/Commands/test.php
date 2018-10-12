<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tools\Collectors\Rules\lewen123\LewenRule;
use App\Models\BookCollectionErrorLog;
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
        for($i=20001;$i<=83798;$i++){
            try{
                $LewenRule = new LewenRule("http://www.lewen123.com/lewen/$i.html");
                $LewenRule->request()->get();
            }catch (\Exception $exception){
                BookCollectionErrorLog::create([
                    'from_url'=>"http://www.lewen123.com/lewen/$i.html",
                    'error'=>$exception->getMessage(),
                ]);
            }
			usleep(300000);
            //sleep(1);
        }

        dd("ok");


    }
}
