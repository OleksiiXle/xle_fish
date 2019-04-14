<?php

namespace app\modules\adminx;

class Adminx extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\adminx\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!isset(\Yii::$app->i18n->translations['rbac-admin'])) {
            \Yii::$app->i18n->translations['rbac-admin'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath' => '@app/modules/adminx/messages'
            ];
        }


        // custom initialization code goes here
    }
}
