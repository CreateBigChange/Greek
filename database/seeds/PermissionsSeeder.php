<?php

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;

class PermissionsSeeder extends Seeder {

    public function run()
    {
		$fileSystem = new Filesystem();
		$database = $fileSystem->get(base_path('database/seeds') . '/sql/' . 'permissions.sql');
		DB::connection()->getPdo()->exec($database);
    }
}
