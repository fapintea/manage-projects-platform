<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Diploma\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_names = array('Profesor', 'Admin', 'Superadmin', 'Student licenţă', 'Student Master');

        foreach($role_names as $role_name) {
            $role = new Role();
            $role->role_name = $role_name;
            $role->save();
            $this->command->info('Adding role - ' . $role_name);
        }
    }
}
