<?php

namespace Database\Seeders;

use App\Models\Role;
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
            'name' => 'admin',
            'email' => 'admin@app.com',
            'mobile' => '01019030515',
            'email_verified_at' => now(),
            'password' => bcrypt('123456'),
            'role_id' => 1,
            'status' => 1
        ]);
    }
}
