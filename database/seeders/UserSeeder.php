<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => '测试用户',
            'email' => 'test@dotexam.test',
            'email_verified_at' => now(),
            'password' => 'password',
        ]);
    }
}
