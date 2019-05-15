<?php

namespace app\modules\adminx\components;


class Config extends \yii\base\BaseObject
{
    static $permCacheKey = 'perm';
    static $permCacheKeyDuration = 0; //-- бесконечно, если задать число - это секунды жизни
    static $guestControlDuration = 3600  ; //-- 0 - бесконечно


}