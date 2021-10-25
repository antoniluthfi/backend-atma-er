<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
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
                'nama' => 'Usman Sidomulyo',
                'deskripsi' => 'Grup administrasi usman sidomulyo',
                'jumlah_anggota' => '2',
                'status' => '1'
            ]
        ];

        Group::insert($array);
    }
}
