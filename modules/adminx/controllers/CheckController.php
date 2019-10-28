<?php

namespace app\modules\adminx\controllers;

use app\components\conservation\ActiveDataProviderConserve;
use app\controllers\MainController;
use app\models\User;
use app\modules\adminx\components\AccessControl;
use app\modules\adminx\models\Assignment;
use app\modules\adminx\models\filters\UControlFilter;
use app\modules\adminx\models\filters\UserActivityFilter;
use app\modules\adminx\models\filters\UserFilter;
use app\modules\adminx\models\form\ChangePassword;
use app\modules\adminx\models\form\ForgetPassword;
use app\modules\adminx\models\form\Login;
use app\modules\adminx\models\form\PasswordResetRequestForm;
use app\modules\adminx\models\form\ResetPasswordForm;
use app\modules\adminx\models\form\Signup;
use app\modules\adminx\models\form\SignupService;
use app\modules\adminx\models\form\Update;
use app\modules\adminx\models\UserM;
use yii\base\InvalidParamException;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;

class CheckController extends MainController
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
                        'guest-control', 'user-control',
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
        return $behaviors;
    }

    public function actionUserControl()
    {
        $dataProvider = new ActiveDataProviderConserve([
            'filterModelClass' => UserActivityFilter::class,
            'conserveName' => 'userActivityGrid',
            'pageSize' => 20,
        ]);

        return $this->render('usersGrid',[
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGuestControl()
    {
        $dataProvider = new ActiveDataProviderConserve([
            'filterModelClass' => UControlFilter::class,
            'conserveName' => 'userAdminGrid',
            'pageSize' => 15,
        ]);
        return $this->render('guestsGrid',[
            'dataProvider' => $dataProvider,
        ]);

    }

}