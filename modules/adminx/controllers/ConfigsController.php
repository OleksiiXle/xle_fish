<?php

namespace app\modules\adminx\controllers;

use app\components\configs\models\Configs;
use app\controllers\MainController;
use app\modules\adminx\components\AccessControl;
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
                         'index', 'create', 'update', 'delete'
                    ],
                    'roles'      => ['adminCRUD' ],
                ],
            ],
            'denyCallback' => function ($rule, $action) {
                \yii::$app->getSession()->addFlash("warning",\Yii::t('app', "Действие запрещено"));
                return $this->redirect(\Yii::$app->request->referrer);

        }
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
    public function actionUpdate($id)
    {
        $model = Configs::findOne($id);
        if (\Yii::$app->getRequest()->isPost) {
            $data = \Yii::$app->getRequest()->post('Configs');
            if (isset($data['reset-button'])){
                return $this->redirect(['index']);
            }
            $model->setAttributes($data);
            if ($model->save(false)) {
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
}