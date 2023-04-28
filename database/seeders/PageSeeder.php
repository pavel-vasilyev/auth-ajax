<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->insert([
            [
                'title' => 'Добро пожаловать!',
                'description' => 'Домашняя страница',
                'keywords' => 'домашняя страница, начальная страница, добро пожаловать',
                'author' => 'Admin',
                'published' => '1',
            ],
            [
                'title' => 'Демо страница',
                'description' => 'Демонстрационная страница первого уровня',
                'keywords' => 'демонстрационная страница',
                'author' => 'Admin',
                'published' => '1',
            ],
        ]);
    }
}
