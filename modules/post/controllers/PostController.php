<?php

namespace app\modules\post\controllers;

use app\components\conservation\ActiveDataProviderConserve;
use app\controllers\MainController;
use app\models\FileUpload;
use app\models\Functions;
use app\models\Upload;
use app\models\UploadPreview;
use app\modules\adminx\components\AccessControl;
use app\modules\post\models\filters\PostFilter;
use app\modules\post\models\Post;
use app\modules\post\models\PostMedia;
use yii\data\ActiveDataProvider;
use \yii\helpers\Url;
use yii\web\UploadedFile;

class PostController extends MainController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'create', 'update', 'delete', 'get-media-preview', 'get-post-media',
                        'create-post-media'],
                    'roles' => ['@'],
                ],
            ],
            /*
            'denyCallback' => function ($rule, $action) {
                if (\Yii::$app->user->isGuest){
                    $redirect = Url::to(\Yii::$app->user->loginUrl);
                    return $this->redirect( $redirect);
                } else {
                    \yii::$app->getSession()->addFlash("warning",\Yii::t('app', "Действие запрещено"));
                    return $this->redirect(\Yii::$app->request->referrer);
                }
            }
            */
        ];
        return $behaviors;
    }

    /**
     * Список постов
     * @return string
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProviderConserve([
            'filterModelClass' => PostFilter::class,
            'conserveName' => 'postGrid',
            'pageSize' => 15,
        ]);
        return $this->render('index',[
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Создать новый пост
     * @return string
     */
    public function actionCreate()
    {
        $model = new Post();
        $model->user_id = \Yii::$app->user->id;
        if (\Yii::$app->request->isPost){
            $_post = \Yii::$app->request->post();
            if (isset($_post['signup-button'])){
                $model->setAttributes($_post['Post']);
                if ($model->savePost()){
                    return $this->redirect(Url::to(['update', 'id' => $model->id]));
                }
            }
        }
        return $this->render('update', ['model' => $model]);
    }

    /**
     * Редактировать пост
     * @return string
     */
    public function actionUpdate($id)
    {
      //  return $this->render('test');
        $model = Post::findOne($id);
        $modelMedia = new PostMedia();
        $modelMedia->post_id = $id;
        $modelMedia->type = PostMedia::TYPE_IMAGE;
        $modelMediaProvider = new ActiveDataProvider([
           'query' => PostMedia::find()
               ->where(['post_id' => $id]),
            'pagination' => [
                'pageSize' => 3,
                ],
            ]);
        if (isset($model)){
            if (\Yii::$app->request->isPost){
                $_post = \Yii::$app->request->post();
                if (isset($_post['signup-button'])){
                    $model->setAttributes($_post['Post']);
                    if ($model->savePost()){
                        return $this->redirect('index');
                    }
                } else {
                    return $this->redirect('index');

                }

            }
            return $this->render('update', [
                'model' => $model,
                'modelMedia' => $modelMedia,
                'modelMediaProvider' => $modelMediaProvider,
                ]);
        } else {
            \yii::$app->getSession()->addFlash("warning",\Yii::t('app', "Информация не найдена"));
            return $this->redirect('index');
        }
    }

    /**
     * Удалить пост
     * @return string
     */
    public function actionDelete($id)
    {
        $r=1;
        if (\Yii::$app->request->isPost){
            $model = Post::findOne($id);
            if ($model->deletePost()){
                \yii::$app->getSession()->addFlash("success",\Yii::t('app', "ok"));
            } else {
                \yii::$app->getSession()->addFlash("warning",\Yii::t('app', "Ошибка удаления поста"));
            }
        }
        return $this->redirect('index');

    }

    /**
     * AJAX загрузка превью изображения при редактировании
     * @return string
     */
    public function actionGetMediaPreview()
    {
        $userId = \Yii::$app->user->id;

        if ($type = \Yii::$app->request->post('type')) {
            $model = new FileUpload();
            if ($model->getInstance($type)){
                if ($model->uploadToTmp($userId)){
                    $this->result = [
                        'status' =>true,
                        'data' => [
                            'fileName' => $model->name,
                            'webFullFileName' => $model->webFullFileName,
                        ]
                    ];
                } else {
                    $this->result['data'] = $model->getErrors();
                }
            } else {
                $this->result['data'] = $model->getErrors();
            }
        }
        return $this->asJson($this->result);
    }

    /**
     * AJAX загрузка списка изображений при редактировании
     * @return string
     */
    public function actionGetPostMedia($id)
    {
        $t=1;
        $postMedia = PostMedia::find()
            ->where(['post_id' => $id])
            ->all();
        return $this->renderAjax('_postMedia',['postMedia' => $postMedia]);
    }


    /**
     * AJAX созранение нового изображения
     */
    public function actionCreatePostMedia()
    {
        $_post = \Yii::$app->request->post();
        if (isset($_post) && isset($_post['PostMedia'])){
            $model = new PostMedia();
            $model->setAttributes($_post['PostMedia']);
            if ($model->savePostMedia(\Yii::$app->user->id)){
                $this->result = [
                    'status' => true,
                    'data' => $model->id,
                ];
            } else {
                $this->result['data'] = $model->getErrors();
            }

        }
        return $this->asJson($this->result);
    }



}
