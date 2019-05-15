<?php

namespace app\modules\adminx\controllers;

use app\controllers\MainController;
use app\models\Translation;
use app\modules\adminx\components\AccessControl;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;

class TranslationController extends MainController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow'      => true,
                    'actions'    => [
                         'index', 'create', 'update', 'delete'
                    ],
                    'roles'      => ['adminCRUD' ],
                ],
                [
                    'allow'      => true,
                    'actions'    => [
                         'change-language',
                    ],
                    'roles'      => ['@' , '?' ],
                ],
            ],
                /*
            'denyCallback' => function ($rule, $action) {
                \yii::$app->getSession()->addFlash("warning",\Yii::t('app', "Действие запрещено"));
                return $this->redirect(\Yii::$app->request->referrer);

        }
        */
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'delete' => ['post'],
            ],

        ];
        return $behaviors;
    }


    /**
     * +++ Список всех
     * @return mixed
     */
    public function actionIndex() {
     //   $r = Translation::getDictionary('app', 'ru-RU');
        $r = \Yii::t('app', 'Перевод');
        $dataProvider = new ActiveDataProvider([
            'query' => Translation::find()
            ->where(['language' => \Yii::$app->language])
            ,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
        return $this->render('index',[
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * +++ Регистрация нового
     * @return string
     */
    public function actionCreate()
    {
        $model = new Translation();
        if (\Yii::$app->getRequest()->isPost) {
            $data = \Yii::$app->getRequest()->post('Translation');
            if (isset($data['reset-button'])){
                return $this->redirect(['index']);
            }
            $model->setAttributes($data);
            if ($model->saveTranslation()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * +++ Регистрация нового
     * @return string
     */
    public function actionUpdate($id)
    {
        $model = Translation::findOne($id);
        $model->setLanguages();
        if (\Yii::$app->getRequest()->isPost) {
            $data = \Yii::$app->getRequest()->post('Translation');
            if (isset($data['reset-button'])){
                return $this->redirect(['index']);
            }
            $model->setAttributes($data);
            if ($model->saveTranslation()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * +++ Удаление
     * @return string
     */
    public function actionDelete($tkey)
    {
        if (\Yii::$app->request->isPost){
            $userDel = Translation::deleteAll(['tkey' => $tkey]);
            if ($userDel === 0){
                \yii::$app->getSession()->addFlash("warning","Ошибка при удалении.");
            }
        }
        return $this->redirect('index');

    }


    public function actionChangeLanguage()
    {
        try {
            $language    = \Yii::$app->getRequest()->get('language');
            if (!empty($language)){
                $user = \Yii::$app->user->id;
                if (!empty($user)){
                    $ret = \Yii::$app->conservation->setConserveDB('language', $language);
                } else {
                    $session = \Yii::$app->session;
                    $session->set('language', $language);
                }
            }
        } catch (\Exception $e) {
            $this->result['data'] = $e->getMessage();
        }
        return $this->redirect(\Yii::$app->request->referrer);
   //     return $this->goBack();
    }

}