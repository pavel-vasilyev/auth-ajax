<?php

namespace App\View\Components\Layouts;

use Illuminate\View\Component;
use App\Models\Page;
use Illuminate\Http\Request;

class Main extends Component
{
    /**
     * Это класс компонента resources/views/components/layouts/main.blade.php - макета, общего для всех/большинства страниц сайта
     */

    /**
     * Свойство $data принимает в себя параметры (массив $data), переданные контроллером страницы в представление
     */
    protected array $data;
    protected object $request;

    /**
     * Следующие свойства - параметры, обязательные для всех страниц (SEO, статистика и др.),
     * Они будут переданы в макет resources/views/components/layouts/main.blade.php функцией view метода render.
     * У этих свойств область видимости обязательно должна быть public,
     * тогда в методе render они будут переданы в функцию view АВТОМАТИЧЕСКИ
     */

    public int $id = 1;
    public string $title = '';
    public string $description = '';
    public string $keywords = '';
    public string $author = '';
    public array $modal = array();

    /**
     * layout constructor.
     * @param $data
     * Конструктор помещает принятые параметры в одноимённый массив:
     */
    public function __construct(Request $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }

    /**
     * Get the view / contents that represent the component.
     * @return \Illuminate\Contracts\View\View|\Closure|string
     *
     * В методе render задействована модель Page для сбора обязательной информации о текущей странице
     */
    public function render()
    {
        /**
        * Вытаскиваем из БД информацию о странице:
        */
        $page = Page::where('id', $this->data['id'])->where('published','1')->first();
        if (!$page) {
            return view('errors.404'); // 404, если страница не опубликована или не существует
        }

        $this->id = $page->id;
        $this->title = $page->title;
        $this->description = $page->description;
        $this->keywords = $page->keywords;
        $this->author = $page->author;

        /**
         * На случай, когда сразу после загрузки страницы требуется вывести модальное окно с сообщением:
         */
        if ($this->request->session()->has('onload-modal')){
            $this->modal = [
                'modalClass' => ' onload-show',
                'modalTitle' => $this->request->session()->get('onload-modal.title'),
                'modalBody' => $this->request->session()->get('onload-modal.message'),
            ];
            $this->request->session()->forget('onload-modal');
        }

        return view('components.layouts.main');
    }
}
