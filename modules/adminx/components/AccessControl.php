<?php

namespace app\modules\adminx\components;

use app\components\AccessHelper;
use yii\web\ForbiddenHttpException;
use yii\base\Module;
use Yii;
use yii\web\Response;
use yii\web\User;
use yii\di\Instance;

class AccessControl extends \yii\filters\AccessControl
{
    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @param  User $user the current user
     * @throws ForbiddenHttpException if the user is already logged in.
     */
    protected function denyAccess($user)
    {
        $r=1;
        if ($user->getIsGuest()) {
            $user->loginRequired();
        } else {
            if (\Yii::$app->request->isAjax){
                throw new ForbiddenHttpException(\Yii::t('app', "Действие запрещено"));
            } else {
                \yii::$app->getSession()->addFlash("warning",\Yii::t('app', "Действие запрещено"));
            }

        }
    }

}
