<?php

namespace app\modules\adminx\controllers;

use app\components\conservation\ActiveDataProviderConserve;
use app\components\conservation\models\Conservation;
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
use app\modules\adminx\models\form\SignupService;
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
                    'actions' => ['login', 'signup', 'signup-confirm', 'test', 'request-password-reset', 'reset-password'],
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
                        'index', 'php-info', 'test' , 'conservation'
                    ],
                    'roles'      => ['adminView','adminCRUD' ],
                ],
                [
                    'allow'      => true,
                    'actions'    => [
                         'signup-by-admin', 'update', 'delete'
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
    public function actionSignupByAdmin()
    {
        $model = new Signup();
        if (\Yii::$app->getRequest()->isPost) {
            $data = \Yii::$app->getRequest()->post('Signup');
            $model->setAttributes($data);
            $model->first_name = $data['first_name'];
            $model->middle_name =  $data['middle_name'];
            $model->last_name =  $data['last_name'];
            if ($model->signup(false)) {
                return $this->redirect(['/site/index']);
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * +++ Регистрация нового пользователя с подтверждением Емейла
     * @return string
     */
    public function actionSignup()
    {
        $model = new Signup();
        if (\Yii::$app->getRequest()->isPost) {
            $data = \Yii::$app->getRequest()->post('Signup');
            $model->setAttributes($data);
            $model->first_name = $data['first_name'];
            $model->middle_name =  $data['middle_name'];
            $model->last_name =  $data['last_name'];

            if ($user = $model->signup(true)) {
                \Yii::$app->session->setFlash('success', \Yii::t('app', 'Check your email to confirm the registration'));
                return $this->goHome();
            } else {
                \Yii::$app->session->setFlash('error', \Yii::t('app', 'Ошибка отправки токена'));
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * +++ Подтверждение регистрации по токену
     * @return string
     */
    public function actionSignupConfirm($token)
    {
        $signupService = new Signup();

        try{
            $signupService->confirmation($token);
            \Yii::$app->session->setFlash('success', \Yii::t('app', 'Регистрация успешно подтверждена'));
        } catch (\Exception $e){
            \Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->goHome();
    }

    /**
     * +++ Login
     * @return string
     */
    public function actionLogin()
    {
        $model = new Login();
        if ($model->load(\Yii::$app->getRequest()->post()) && $model->login()) {
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
        $ass = new Assignment($id);
        $assigments = $ass->getItemsXle();
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
     * Запрос на смену пароля через Емейл
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                \Yii::$app->session->setFlash('success',
                    \Yii::t('app', 'На Ваш електронный адрес отправлено письмо для изменения пароля'));
            } else {
                \Yii::$app->session->setFlash('error', \Yii::t('app', 'Не удалось сбросить пароль с помощью Email'));
            }
            return $this->goHome();
        }

        return $this->render('passwordResetRequest', [
            'model' => $model,
        ]);
    }

    /**
     * Смена пароля по токену из Емейла
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
            \Yii::$app->session->setFlash('success', \Yii::t('app', 'Новый пароль сохранен'));
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
        $t = 1;
        return $this->render('test');
    }

    public function actionConservation()
    {
        $user_id = \Yii::$app->user->id;
        $conserve = null;
        if (!empty($user_id)){
            $conservation = Conservation::find()
                ->where(['user_id' => $user_id])
                ->asArray()
                ->all();
        }
        return $this->render('conservation' , ['conservation' => $conservation]);

    }

}