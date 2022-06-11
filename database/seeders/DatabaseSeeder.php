<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;



class DatabaseSeeder extends Seeder
{


    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {


        DB::statement('SET FOREIGN_KEY_CHECK = 0');
        $this->call([

            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            TransactionSeeder::class

        ]);
    }
}
