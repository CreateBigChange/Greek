<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AreaSeeder::class);
        $this->call(AdminUserSeeder::class);

        $this->call(RolesSeeder::class);
        $this->call(PermissionsSeeder::class);

        $this->call(AdminPermissionRolesSeeder::class);
        $this->call(AdminUserRolesSeeder::class);

        $this->call(StoreInfosSeeder::class);
        $this->call(StoreUsersSeeder::class);

    }
}
