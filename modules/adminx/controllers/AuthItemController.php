<?php

namespace app\modules\adminx\controllers;

use app\components\conservation\ActiveDataProviderConserve;
use app\controllers\MainController;
use app\modules\adminx\models\AuthItem;
use app\modules\adminx\models\AuthItemX;
use app\modules\adminx\models\filters\AuthItemFilter;
use app\modules\adminx\models\filters\PermissionFilter;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\rbac\Item;



class AuthItemController extends MainController
{


    public function actionIndex(){
        $dataProvider = new ActiveDataProviderConserve([
            'filterModelClass' => AuthItemFilter::class,
            'conserveName' => 'authItemAdminGrid',
            'pageSize' => 15,
        ]);
        return $this->render('index',[
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($type){
        $model = new AuthItemX();
        $model->type = $type;
        if ($model->load(\Yii::$app->getRequest()->post())) {
            if ($model->save()) {
                return $this->redirect(['/adminx/auth-item/update', 'name' => $model->name]);
            }
        }
        return $this->render('create',
            [
                'model' => $model,
            ]);
    }

    public function actionUpdate($name ){
        $model =  AuthItemX::find()
            ->where(['name' => $name])
            ->one();
        if (isset($model)){
            $assigments = AuthItemX::getItemsXle($model->type, $name);
            if ($model->load(\Yii::$app->getRequest()->post())) {
                if (\Yii::$app->getRequest()->post('delete-button')){
                    $manager = \Yii::$app->authManager;
                    $item = ($model->type == AuthItemX::TYPE_ROLE) ?
                        $manager->getRole($model->name) :
                        $manager->getPermission($model->name);
                    $manager->remove($item);
                    return $this->redirect('/adminx/auth-item');
                }
                if ($model->save()) {
                    return $this->redirect('/adminx/auth-item');
                }
            }
            return $this->render('update', [
                'model' => $model,
                'assigments' => $assigments,
                ]);

        } else {
            return $this->redirect('/adminx/auth-item');
        }
    }

    public function actionDelete(){

    }

    /**
     * +++ Назначение итему ролей, разрешений, роутов
     * @param string $id
     * @param string $type (roles, permissions, routs)
     * @param array $items
     * @return string
     */
    public function actionAssign(){
        try {
            $name    = \Yii::$app->getRequest()->post('name');
            $type    = \Yii::$app->getRequest()->post('type');
            $items = \Yii::$app->getRequest()->post('items', []);
            $auth = \Yii::$app->getAuthManager();
            $parent = $type == Item::TYPE_ROLE ? $auth->getRole($name) : $auth->getPermission($name);
            foreach ($items as $itemName){
                if (($item = $auth->getPermission($itemName)) == null){
                    $item = $auth->getRole($itemName);
                }
                $success = $auth->addChild($parent, $item);
            }
            $assigments = AuthItemX::getItemsXle($type, $name);

            $this->result =[
                'status' => true,
                'data'=>  $assigments
            ];
        } catch (\Exception $e) {
            $this->result['data'] = $e->getMessage();
        }
        return $this->asJson($this->result);

        $name    = \Yii::$app->getRequest()->post('name');
        $type    = \Yii::$app->getRequest()->post('type');
        $items = \Yii::$app->getRequest()->post('items', []);
        $auth = \Yii::$app->getAuthManager();
        $parent = $type == Item::TYPE_ROLE ? $auth->getRole($name) : $auth->getPermission($name);
        foreach ($items as $itemName){
            if (($item = $auth->getPermission($itemName)) == null){
                $item = $auth->getRole($itemName);
            }
            $success = $auth->addChild($parent, $item);
        }
        \Yii::$app->getResponse()->format = 'json';
        $assigments = AuthItemX::getItemsXle($type, $name);
        return $assigments;
    }

    /**
     * +++ Удаление у итема ролей, разрешений, роутов
     * @param string $id
     * @param string $type (roles, permissions, routs)
     * @param array $items
     * @return string
     */
    public function actionRevoke() {
        try {
            $name    = \Yii::$app->getRequest()->post('name');
            $type    = \Yii::$app->getRequest()->post('type');
            $items = \Yii::$app->getRequest()->post('items', []);
            $auth = \Yii::$app->getAuthManager();
            $parent = $type == Item::TYPE_ROLE ? $auth->getRole($name) : $auth->getPermission($name);
            foreach ($items as $itemName){
                if (($item = $auth->getPermission($itemName)) == null){
                    $item = $auth->getRole($itemName);
                }
                $success = $auth->removeChild($parent, $item);
            }
            $assigments = AuthItemX::getItemsXle($type, $name);

            $this->result =[
                'status' => true,
                'data'=>  $assigments
            ];
        } catch (\Exception $e) {
            $this->result['data'] = $e->getMessage();
        }
        return $this->asJson($this->result);


        $name    = \Yii::$app->getRequest()->post('name');
        $type    = \Yii::$app->getRequest()->post('type');
        $items = \Yii::$app->getRequest()->post('items', []);
        $auth = \Yii::$app->getAuthManager();
        $parent = $type == Item::TYPE_ROLE ? $auth->getRole($name) : $auth->getPermission($name);
        foreach ($items as $itemName){
            if (($item = $auth->getPermission($itemName)) == null){
                $item = $auth->getRole($itemName);
            }
            $success = $auth->removeChild($parent, $item);
        }
        \Yii::$app->getResponse()->format = 'json';
        $assigments = AuthItemX::getItemsXle($type, $name);
        return $assigments;
    }




}
