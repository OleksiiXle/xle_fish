<?php

namespace app\modules\adminx\controllers;

use app\controllers\MainController;
use app\modules\adminx\components\AccessControl;
use app\modules\adminx\models\Configs;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;

class ConfigsController extends MainController
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
                         'index', 'create', 'update', 'delete', 'update-configs'
                    ],
                    'roles'      => ['adminCRUD' ],
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
       //
        // $configs = new Configs();
    //    $configs->getConfigs();
        $dataProvider = new ActiveDataProvider([
            'query' => Configs::find(),
            'pagination' => [
                'pageSize' => 20,
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
        $model = new Configs();
        if (\Yii::$app->getRequest()->isPost) {
            $data = \Yii::$app->getRequest()->post('Configs');
            if (isset($data['reset-button'])){
                return $this->redirect(['index']);
            }
            $model->setAttributes($data);
            if ($model->save()) {
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
    public function actionUpdate()
    {
        $model = new Configs();
        $model->getConfigs();

        if (\Yii::$app->getRequest()->isPost) {
            $data = \Yii::$app->getRequest()->post('Configs');
            if (isset($data['reset-button'])){
                return $this->goBack();
            }
            $model->setAttributes($data);
            if ($model->setConfigs()) {
                return $this->goBack();
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
    public function actionDelete($id)
    {
        if (\Yii::$app->request->isPost){
            $userDel = Configs::findOne($id)->delete();
            if ($userDel === 0){
                \yii::$app->getSession()->addFlash("warning","Ошибка при удалении.");
            }
        }
        return $this->redirect('index');

    }

    public function actionUpdateConfigs()
    {
        $configs = new Configs();
        $configs->getConfigs();
        return $this->redirect('index');

    }


}