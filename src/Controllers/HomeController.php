<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{

    public function show(){
        $data = [
            'id' => 1,
            'category' => 'home',
            'var1' => 'Эта строка сформирована в контроллере представления страницы и передана в представление в составе массива $data в качестве параметра функции view.',
            'var2' => 'Блок комментариев'
        ];

        return view('home', ['data' => $data]);
    }
}
