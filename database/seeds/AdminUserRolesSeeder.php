<?php

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;

class AdminUserRolesSeeder extends Seeder {

    public function run()
    {
		$fileSystem = new Filesystem();
		$database = $fileSystem->get(base_path('database/seeds') . '/sql/' . 'admin_user_roles.sql');
		DB::connection()->getPdo()->exec($database);
    }
}
