<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = ['ahmed', 'mohamed'];

        foreach ($clients as $client) {

            \App\Models\Client::create([
               'name' => $client,
               'phone' => '011111112',
               'address' => 'haram',
            ]);

        }//end of foreach

    }//end of run

}//end of seeder
