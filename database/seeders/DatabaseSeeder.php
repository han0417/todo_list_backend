<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_store_accounts')->insert([
            'name'    => 'admin',
            'account' => 'admin',
            'password' => Hash::make('123qwe'),
        ]);
    }
}
