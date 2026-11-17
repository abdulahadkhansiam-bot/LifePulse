<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('Password123!');

        // Member 1: Rifaat Nuha (Voluntary Donor)
        User::updateOrCreate(
            ['email' => 'rifaat@lifepulse.org'],
            [
                'name' => 'Rifaat Nuha',
                'password' => $defaultPassword,
                'role' => User::ROLE_DONOR,
                'blood_group' => 'B+',
                'phone' => '01711000001',
                'city' => 'Dhaka',
                'address' => 'Dhanmondi, Dhaka',
                'status' => 'active',
            ]
        );

        // Member 2: Siam (Emergency Requester)
        User::updateOrCreate(
            ['email' => 'siam@lifepulse.org'],
            [
                'name' => 'Md Abdul Ahad Khan Siam',
                'password' => $defaultPassword,
                'role' => User::ROLE_REQUESTER,
                'blood_group' => 'O+',
                'phone' => '01711000002',
                'city' => 'Dhaka',
                'address' => 'Mirpur, Dhaka',
                'status' => 'active',
            ]
        );

        // Member 3: Mashud Hasan (Hospital Staff)
        User::updateOrCreate(
            ['email' => 'mashud@lifepulse.org'],
            [
                'name' => 'Mashud Hasan',
                'password' => $defaultPassword,
                'role' => User::ROLE_HOSPITAL,
                'blood_group' => 'A+',
                'phone' => '01711000003',
                'city' => 'Dhaka',
                'address' => 'Dhaka Medical Area',
                'status' => 'active',
            ]
        );

        // Member 4: Reehay Sabah Samama (System Admin)
        User::updateOrCreate(
            ['email' => 'reehay@lifepulse.org'],
            [
                'name' => 'Reehay Sabah Samama',
                'password' => $defaultPassword,
                'role' => User::ROLE_ADMIN,
                'blood_group' => 'AB+',
                'phone' => '01711000004',
                'city' => 'Dhaka',
                'address' => 'Gulshan, Dhaka',
                'status' => 'active',
            ]
        );
    }
}
