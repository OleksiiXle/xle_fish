<?php
namespace app\components\widgets\selectXle;

use yii\base\Widget;

/**
 * Class SelectXleWidget
 * Иммитация селекта с передачей js-функции для вызова при выборе определенного пункта
 * @package app\components\widgets\selectXle
 */
class SelectXleWidget extends Widget
{
    /**
     * Массив $ключ => $текст, например
     *      Array(
     *       [ru-RU] => Русский
     *       [uk-UK] => Українська
     *       [en-US] => English
     *      )
     * @var
     */
    public $listData;
    /**
     * Дефолтное выбранное значение - $ключ из $listData
     * @var
     */
    public $selectedItem;
    /**
     * JS - функция clickFunction(item), которая будет вызываться при клике на пункт селекта, параметром
     * ей передается $ключ селекта, на который был клик, может передаваться из php, а может быть объявлена в
     * загруженном js-файле
     * например,  function clickFunction(item) {
     *               document.location.href = '/adminx/translation/change-language?language=' + item;
     *            }
     * @var
     */
    public $jsFunction='';
    /**
     * Пользовательские стили для елементов и фона селекта
     * например, 'userStyles' => [
     *              'listItem' => [
     *                  'font-weight' => 300,
     *                  'font-size' => 'small',
     *                  'color' => 'blue',
     *              ],
     *              'itemsArea' => [
     *                  'background' => '#eeeeee',
     *                  'border' => '2px solid #bdbdbd',
     *              ],
     *            ],
     * @var
     */
    public $userStyles;

    public function run()
    {
        SelectXleAssets::register($this->getView());
        return $this->render('selectXle',
            [
                'listData' => $this->listData,
                'selectedItem' => $this->selectedItem,
                'jsFunction' => $this->jsFunction,
                'userStyles' => $this->userStyles,
            ]);
    }

}
