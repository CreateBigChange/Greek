<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StoreCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storeCount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '店铺数据统计';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('sssssssss');
    }
}
