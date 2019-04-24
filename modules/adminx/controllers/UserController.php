<?php

namespace app\modules\adminx\controllers;

use app\components\conservation\ActiveDataProviderConserve;
use app\controllers\MainController;
use app\models\User;
use app\modules\adminx\components\AccessControl;
use app\modules\adminx\models\Assignment;
use app\modules\adminx\models\filters\UserFilter;
use app\modules\adminx\models\form\ChangePassword;
use app\modules\adminx\models\form\ForgetPassword;
use app\modules\adminx\models\form\Login;
use app\modules\adminx\models\form\PasswordResetRequestForm;
use app\modules\adminx\models\form\ResetPasswordForm;
use app\modules\adminx\models\form\Signup;
use app\modules\adminx\models\form\Update;
use app\modules\adminx\models\UserM;
use yii\base\InvalidParamException;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;

class UserController extends MainController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['login', 'signup', 'test', 'request-password-reset', 'reset-password'],
                    'roles' => ['?'],
                ],
                [
                    'allow' => true,
                    'actions' => ['logout', 'test', 'change-password', 'update-profile'],
                    'roles' => ['@'],
                ],
                [
                    'allow'      => true,
                    'actions'    => [
                        'index', 'php-info', 'test'
                    ],
                    'roles'      => ['adminView','adminCRUD' ],
                ],
                [
                    'allow'      => true,
                    'actions'    => [
                         'signup', 'update', 'delete'
                    ],
                    'roles'      => ['adminView','adminCRUD' ],
                ],
            ],
            'denyCallback' => function ($rule, $action) {
                \yii::$app->getSession()->addFlash("warning","Действие запрещено");
                return $this->redirect(\Yii::$app->request->referrer);

        }
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'delete' => ['post'],
                'logout' => ['post'],
                'activate' => ['post'],
            ],

        ];
        return $behaviors;
    }


    /**
     * +++ Список всех пользователей
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProviderConserve([
            'filterModelClass' => UserFilter::class,
            //  'baseModel' => Dictionary::find(),
            'conserveName' => 'userAdminGrid',
            'pageSize' => 15,
            'sort' => ['attributes' => [
                'id',
                'username',
                'nameFam' => [
                    'asc' => [
                        'user_data.last_name' => SORT_ASC,
                    ],
                    'desc' => [
                        'user_data.last_name' => SORT_DESC,
                    ],
                ],
                'lastRoutTime' => [
                    'asc' => [
                        'user_data.last_rout_time' => SORT_ASC,
                    ],
                    'desc' => [
                        'user_data.last_rout_time' => SORT_DESC,
                    ],
                ],
                'lastRout' => [
                    'asc' => [
                        'user_data.last_rout' => SORT_ASC,
                    ],
                    'desc' => [
                        'user_data.last_rout' => SORT_DESC,
                    ],
                ],
            ]],

        ]);
        return $this->render('index',[
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * +++ Регистрация нового пользователя Администратором
     * @return string
     */
    public function actionSignup()
    {
        $model = new Signup();
        //  $model = new User();
        //  $model->scenario = User::SCENARIO_REGISTRATION;
        //  if ($model->load(Yii::$app->getRequest()->post())) {
        if (\Yii::$app->getRequest()->isPost) {
            $data = \Yii::$app->getRequest()->post('Signup');
            $model->setAttributes($data);
            $model->first_name = $data['first_name'];
            $model->middle_name =  $data['middle_name'];
            $model->last_name =  $data['last_name'];

            if ($user = $model->signup()) {
                return $this->redirect(['/site/index']);
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * +++ Login
     * @return string
     */
    public function actionLogin()
    {
        $model = new Login();
        if ($model->load(\Yii::$app->getRequest()->post()) && $model->login()) {
            $query = 'SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode, "ONLY_FULL_GROUP_BY,", ""))';
            \Yii::$app->db->createCommand($query)->execute();
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     *+++ Logout
     * @return string
     */
    public function actionLogout(){
        \Yii::$app->getUser()->logout();
        //   return $this->goHome();
        return $this->redirect('/site/index');
    }

    /**
     * +++ Редактирование профиля пользователя администратором
     * @return string
     */
    public function actionUpdate($id)
    {
        $model = UserM::findOne($id);
        //  $model->scenario = User::SCENARIO_UPDATE;

        $ass = new Assignment($id);
        $assigments = $ass->getItemsXle();


        //  if ($model->load(Yii::$app->getRequest()->post())) {
        if (\Yii::$app->getRequest()->isPost) {
            $data = \Yii::$app->getRequest()->post('UserM');
            $model->setAttributes($data);

            if ($model->save()) {
                return $this->redirect('/adminx/user');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'user_id' => $id,
            'assigments' => $assigments,

        ]);
    }

    /**
     * +++ Редактирование профиля пользователя пользователем
     * @return string
     */
    public function actionUpdateProfile()
    {
        $id = \Yii::$app->user->getId();
        if (!empty($id)){
            $model = Update::findOne($id);
            //  $model->scenario = User::SCENARIO_UPDATE;
            $model->first_name = $model->userDatas->first_name;
            $model->middle_name = $model->userDatas->middle_name;
            $model->last_name = $model->userDatas->last_name;

            if (\Yii::$app->getRequest()->isPost) {
                $data = \Yii::$app->getRequest()->post('Update');
                $model->setAttributes($data);
                $model->first_name = $data['first_name'];
                $model->middle_name =  $data['middle_name'];
                $model->last_name =  $data['last_name'];

                if ($user = $model->updateUser()) {
                    return $this->goHome();
                }
            }

            return $this->render('updateProfile', [
                'model' => $model,
                'user_id' => $id,

            ]);
        } else {
            \yii::$app->getSession()->addFlash("warning","Неверный ИД пользователя");
            return $this->redirect(\Yii::$app->request->referrer);

        }
    }

    /**
     * +++ Удаление профиля пользователя
     * @return string
     */
    public function actionDelete($id)
    {
        if (\Yii::$app->request->isPost){
            $userDel = UserM::deleteAll($id);
            if ($userDel === 0){
                \yii::$app->getSession()->addFlash("warning","Ошибка при удалении.");
            }
        }
        return $this->redirect('index');

    }

    /**
     * Change password
     * @return string
     */
    public function actionChangePassword()
    {
        $model = new ChangePassword();
        if ($model->load(\Yii::$app->getRequest()->post()) && $model->change()) {
            return $this->goHome();
        }
        return $this->render('changePassword', [
            'model' => $model,
        ]);
    }

    /**
     * ----- Set new password
     * @return string
     */
    public function actionForgetPassword()
    {

        $model = new ForgetPassword();

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->validate()) {// && $model->forgetPassword()
            $res = $model->forgetPassword();

            if(!$res){
                \Yii::$app->getSession()->setFlash('warning', 'Ошибка');
                return $this->goHome();
            }elseif($res){
                \Yii::$app->getSession()->setFlash('success', 'Новый пароль отправлен Вам электронной почтой');
                return $this->goHome();
            }
        }

        return $this->render('forgetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                \Yii::$app->session->setFlash('success', 'Вам отправлено сообщение');
            } else {
                \Yii::$app->session->setFlash('error', 'Не удалось сбросить пароль с помощью Email.');
            }
            return $this->goHome();
        }

        return $this->render('passwordResetRequest', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            \Yii::$app->session->setFlash('success', 'New password was saved.');
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,]);
      }



















    public function actionPhpInfo()
    {
        return $this->render('phpinfo');
    }






    public function actionTest()
    {
        $menager = \Yii::$app->authManager;
        $menager->invalidateCache();
        return $this->render('test');
    }

}