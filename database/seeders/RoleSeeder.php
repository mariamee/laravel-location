<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'role' => 'Admin',
            'created_at' => Carbon::now()
        ]);

        DB::table('roles')->insert([
            'role' => 'Client',
            'created_at' => Carbon::now()
        ]);

        DB::table('roles')->insert([
            'role' => 'Partenaire',
            'created_at' => Carbon::now()
        ]);
    }
}
