<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = ['Programación', 'Estudios', 'Cursos', 'Aprendizaje'];
        for ($i=0; $i < count($tags); $i++) { 
            Tag::factory()->create(["name" => $tags[$i]]);
        }
    }
}
