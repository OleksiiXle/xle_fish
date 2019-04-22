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
}
