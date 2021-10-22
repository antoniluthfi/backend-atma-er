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
        $array = [
            [
                'name' => 'Lintang Luthfiantoni',
                'email' => 'antoniluthfi331@gmail.com',
                'password' => bcrypt('antoni123'),
                'status_akun' => '1',
                'hak_akses' => 'administrator'
            ],
            [
                'name' => 'Tamara Maisah Nurjannah',
                'email' => 'tamara@gmail.com',
                'password' => bcrypt('tamara123'),
                'status_akun' => '1',
                'hak_akses' => 'bendahara'
            ]
        ];

        User::insert($array);
    }
}
