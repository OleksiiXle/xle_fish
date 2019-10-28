<?php

namespace app\modules\adminx\controllers;

use app\controllers\MainController;
use app\modules\adminx\components\AccessControl;
use Yii;
use app\modules\adminx\models\RuleX;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
//use app\modules\adminx\components\Helper;

class RuleController extends MainController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow'      => true,
                    'actions'    => [
                        'index',
                    ],
                    'roles'      => ['adminView', 'adminCRUD', ],
                ],
                [
                    'allow'      => true,
                    'actions'    => [
                        'create', 'update', 'delete'
                    ],
                    'roles'      => ['adminCRUD', ],
                ],
            ],
            /*
            'denyCallback' => function ($rule, $action) {
                \yii::$app->getSession()->addFlash("warning",\Yii::t('app', "Действие запрещено"));
                return $this->redirect(\Yii::$app->request->referrer);

            }
            */
        ];
        return $behaviors;
    }


    public function actionIndex()
    {
        $query = RuleX::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RuleX();
        $rulesClasses = RuleX::getRulesClasses();
        if (Yii::$app->request->isPost){
            $_post = Yii::$app->request->post();
            if (!empty($_post['doAction'])){
                if ($model->load(Yii::$app->request->post())  ) {
                    if (!$model->addRule()){
                        return $this->render('update', ['model' => $model, 'rulesClasses' => $rulesClasses]);
                    }
                    // Helper::invalidate();
                }
            }
            return $this->redirect('index');
        }
        return $this->render('update', ['model' => $model, 'rulesClasses' => $rulesClasses]);
    }

    /**
     * @deprecated  Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param  string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = RuleX::getRule($id);
        $model->mode = 'update';
        $rulesClasses = RuleX::getRulesClasses();
        if (Yii::$app->request->isPost){
            $_post = Yii::$app->request->post();
            if (!empty($_post['doAction'])){
                if ($model->load(Yii::$app->request->post())  ) {
                    if (!$model->save()){
                        return $this->render('update', [
                            'model' => $model,
                            ]);
                    }
                    // Helper::invalidate();
                }
            }
            return $this->redirect('index');
        }
        return $this->render('update', ['model' => $model,]);
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param  string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->request->isPost){
            $model = RuleX::getRule($id);
            $model->delete();
        }
        return $this->redirect('index');

    }

}
