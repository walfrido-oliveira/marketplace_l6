<?php

use Illuminate\Database\Seeder;

class StoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $store = \App\Store::all();

        foreach ($store as $store) {
            $store->products()->save(factory(\App\Product::class)->make());
        }
    }
}
