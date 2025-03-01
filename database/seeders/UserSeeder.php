<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'date_of_birth' => '1980-01-01',
            'gender' => 'male',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
