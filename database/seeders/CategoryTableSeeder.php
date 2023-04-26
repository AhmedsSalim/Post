<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $categories = ['cat one', 'cat two', 'cat three'];

        foreach ($categories as $category) {

            \App\Models\Category::create([
                'ar' => ['name' => $category],
                'en' => ['name' => $category],
            ]);

        }//end of foreach

    }//end of run

}//end of seeder
