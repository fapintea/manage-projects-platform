<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Diploma\User;

class UsersTableSeeder extends Seeder
{   
    private $PATH = 'database/Students.xlsx';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'student',
            'username' => 'student',
            'password' => md5('student'),
            'role_id' => Config::get('constants.BACHELOR_ROLE_ID')
        ]);

        DB::table('users')->insert([
            'name' => 'teacher',
            'username' => 'teacher',
            'password' => md5('teacher'),
            'role_id' => Config::get('constants.TEACHER_ROLE_ID')
        ]);

        DB::table('users')->insert([
            'name' => 'admin',
            'username' => 'admin',
            'password' => md5('admin'),
            'role_id' => Config::get('constants.ADMIN_ROLE_ID')
        ]);

        DB::table('users')->insert([
            'name' => 'superadmin',
            'username' => 'superadmin',
            'password' => md5('superadmin'),
            'role_id' => Config::get('constants.SUPERADMIN_ROLE_ID')
        ]);
    }
}
