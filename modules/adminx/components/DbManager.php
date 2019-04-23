<?php

namespace app\modules\adminx\components;

use app\components\AccessHelper;
use app\modules\adminx\models\UserM as User;
use yii\db\Query;
use yii\rbac\Assignment;
use yii\rbac\Item;


class DbManager extends \yii\rbac\DbManager
{
    public function invalidateCache___()
    {
        $i=1;
        $data = $this->cache->get($this->cacheKey);

        if ($this->cache !== null) {
            $ret = $this->cache->delete($this->cacheKey);
            $this->items = null;
            $this->rules = null;
            $this->parents = null;
        }
     //   $this->_checkAccessAssignments = [];
    }

}
