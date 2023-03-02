<?php

namespace PavelVasilyev\AuthAjax\View;

use Illuminate\View\Component;
use Illuminate\Http\Request;

class Layout extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    protected $request;
    protected $modal = array();

    /**
     * Инициализация $pgTitle здесь в контроллере компонента обязательна!
     * Также важно указать область видимости public.
     *
     * Это касается всех переменных, передаваемых в компонент при его вызове,
     * как, например: <x-layout :pg-title="$pgTitle">
     *
     * Кстати, в этом примере при вызове компонента pg-title - в cebab-case (см. выше),
     * а в классе (см. ниже) - в camelCase. Это обязательно!
     *
     * @var
     */

    public $pgTitle; // - инициализировать обязательно!


    public function __construct(Request $request, $pgTitle)
    {
        $this->request = $request;
        $this->pgTitle = $pgTitle;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        /**
         * Вывод модального окна сразу после загрузки страницы:
         */
        if ($this->request->session()->has('onload-modal')){

            $this->modal = [
                'modalClass' => ' onload-show',
                'modalTitle' => $this->request->session()->get('onload-modal.title'),
                'modalBody' => $this->request->session()->get('onload-modal.message'),
            ];

            $this->request->session()->forget('onload-modal');
        }

        /*
         * Альтернативный вариант - через Helper без необходимости инъекции Request:
         * if (session()->has('onload-modal')){

            $this->modal = [
                'modalClass' => ' onload-show',
                'modalTitle' => session('onload-modal.title'),
                'modalBody' => session('onload-modal.message'),
            ];

            session()->forget('onload-modal');
        }*/

        return view('components.layout', $this->modal);
    }
}
