<?php

namespace App\Http\Controllers;


class HomeController extends Controller
{
    public function show(){
        $params = [
            'pgTitle' => 'Главная страница',
        ];

        return view('home', $params);
    }
}
