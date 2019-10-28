<?php
namespace app\modules\adminx\controllers;

use app\modules\adminx\components\AccessControl;
use Yii;
use app\controllers\MainController;
use app\modules\adminx\models\Assignment;
use yii\filters\VerbFilter;

class AssignmentController extends MainController
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
                        'assign', 'revoke'
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

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'assign' => ['POST'],
                'revoke' => ['POST'],
            ],

        ];
        return $behaviors;
    }


    /**
     * +++ Назначение пользователю ролей, разрешений, роутов
     * @param string $id
     * @param string $type (roles, permissions, routs)
     * @param array $items
     * @return string
     */
    public function actionAssign(){
        try {
            $id    = Yii::$app->getRequest()->post('user_id', []);
            $items = Yii::$app->getRequest()->post('items', []);
            $model = new Assignment($id);
            $success = $model->assign($items);

            $this->result =[
                'status' => true,
                'data'=> $model->getItemsXle(),
            ];
        } catch (\Exception $e) {
            $this->result['data'] = $e->getMessage();
        }
        return $this->asJson($this->result);
    }

    /**
     * +++ Удаление у пользователя ролей, разрешений, роутов
     * @param string $id
     * @param string $type (roles, permissions, routs)
     * @param array $items
     * @return string
     */
    public function actionRevoke() {
        try {
            $id    = Yii::$app->getRequest()->post('user_id', []);
            $items = Yii::$app->getRequest()->post('items', []);
            $model = new Assignment($id);
            $success = $model->revoke($items);

            $this->result =[
                'status' => true,
                'data'=> $model->getItemsXle(),
            ];
        } catch (\Exception $e) {
            $this->result['data'] = $e->getMessage();
        }
        return $this->asJson($this->result);
    }

}
