<?php
namespace app\components\configs;

use app\components\configs\models\Configs;
use app\components\conservation\models\Conservation;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\Html;

class ConfigsComponent extends Component
{
    public $content;
    private $_languages;
    private $_language;

    public function init(){
        parent::init();
        $this->content = Configs::find()
            ->select(['name', 'content'])
            ->indexBy('name')
            ->asArray()
            ->all();
    }

    /**
     * @deprecated
     * @return mixed
     */
    public function getLanguages()
    {
        $this->_languages = [];
        if ($l = Configs::findOne(['name' => 'languages'])){
            $this->_languages = json_decode($l->content, true);
        }
        return $this->_languages;
    }

    /**
     * @deprecated
     * @return mixed
     */
    public function getLanguage()
    {
        $this->_language = \Yii::$app->language;
        if ($l = Configs::findOne(['name' => 'language'])){
            $this->_language = $l->content;
        }
        return $this->_language;
    }


}
?>