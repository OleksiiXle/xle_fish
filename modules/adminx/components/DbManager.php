<?php

namespace app\modules\adminx\components;

use app\components\AccessHelper;
use app\modules\adminx\models\UserM as User;
use yii\db\Query;
use yii\rbac\Assignment;
use yii\rbac\Item;


class DbManager extends \yii\rbac\DbManager
{
}
