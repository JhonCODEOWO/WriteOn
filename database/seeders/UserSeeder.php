<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Create user only in local environment
        if(app()->environment('local')) User::factory()->create(["email" => "prueba@prueba.com", "password"=>"prueba123"]);
        User::factory()->hasNotes(5)->count(10)->create();
    }
}
