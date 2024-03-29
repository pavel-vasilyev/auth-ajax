<?php

namespace App\Http\Controllers;

class ExampleController extends Controller
{
    /**
     * На примере страницы с именем example -
     * контроллер, возвращающий представление страницы и передающий в него массив $data -
     * параметры, подставляемые в контент текущей страницы.
     * В качестве переметров можно передать, например, блок комментариев с формой, хлебные крошки и т.д.
     *
     * Внимание! В $data обязательно указывать значение 'id' - id страницы в таблице 'pages'!
     */
    public function show(){
        $data = [
            'id' => 2,
            'category' => 'example',
            'var1' => 'Эта строка сформирована в контроллере представления страницы и передана в представление в составе массива $data в качестве параметра var1 функции view.',
            'var2' => 'Блок комментариев'
        ];

        return view('example', ['data' => $data]);
    }
}
