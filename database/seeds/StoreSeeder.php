<?php

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fileSystem = new Filesystem();

        $database = $fileSystem->get(base_path('database/seeds') . '/sql/' . 'store_categories.sql');
        DB::connection()->getPdo()->exec($database);

        $database = $fileSystem->get(base_path('database/seeds') . '/sql/' . 'store_infos.sql');
        DB::connection()->getPdo()->exec($database);

        $database = $fileSystem->get(base_path('database/seeds') . '/sql/' . 'store_config.sql');
        DB::connection()->getPdo()->exec($database);

        $database = $fileSystem->get(base_path('database/seeds') . '/sql/' . 'store_users.sql');
        DB::connection()->getPdo()->exec($database);
    }
}
