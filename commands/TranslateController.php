<?php

namespace app\commands;

use app\models\Translation;
use app\modules\adminx\models\form\Signup;
use app\modules\adminx\models\form\Update;
use app\modules\adminx\models\MenuX;
use app\modules\adminx\models\UserData;
use app\modules\adminx\models\UserM;
use Yii;
use yii\console\Controller;
use yii\console\Exception;
use app\modules\adminx\models\User;

class TranslateController extends Controller
{

    public function actionInit(){
        $translations = require(__DIR__ . '/data/transRusInit.php');
        $t = Translation::deleteAll();
        $a = \Yii::$app->db->createCommand('ALTER TABLE translation AUTO_INCREMENT=1')->execute();
        $tkey = 1;
        foreach ($translations as $translation){
            foreach ($translation as $language => $message){
                echo $tkey . ' ' . $language . ' ' . $message . PHP_EOL;
                $t = new Translation();
                $t->category = 'app';
                $t->tkey = $tkey;
                $t->language = $language;
                $t->message = $message;
                if (!$t->save()){
                    echo var_dump($t->getErrors());
                    echo PHP_EOL;
                    return false;
                }
            }
            $tkey++;
        }


    }



}