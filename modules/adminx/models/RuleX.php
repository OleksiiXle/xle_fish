<?php

namespace app\modules\adminx\models;

use yii\db\ActiveRecord;
use yii\rbac\Rule;
use Yii;

class RuleX extends ActiveRecord
{

    /**
     * @var string Rule classname.
     */
    public $className;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['className'], 'required'],
            [['className'], 'string'],
            [['className'], 'classExists']
        ];
    }

    /**
     * Validate class exists
     */
    public function classExists()
    {
        if (!class_exists($this->className)) {
            $message = "Класс не определен ";
            $this->addError('className', $message);
            return;
        }
        if (!is_subclass_of($this->className, Rule::class)) {
            $message ="Неверный класс - необходимо наследоваться от 'yii\rbac\Rule' ";
            $this->addError('className', $message);
        }
        $class = $this->className;
        $rule = new $class();
        if (\Yii::$app->authManager->getRule($rule->name)){
            $message ="Такое правило уже есть";
            $this->addError('className', $message);
        }

    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'className' => 'Класс',
        ];
    }

    public static function getRule($name)
    {
        $ret =  self::findOne(['name' => $name]);
        $rule = \Yii::$app->authManager->getRule($name);
        if (isset($ret) && isset($rule) ){
            $ret->className = $rule::className();
        }
        return $ret;
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $ret = false;
        if ($this->validate()) {
            $manager = Yii::$app->getAuthManager();
            $class = $this->className;
            $rule = new $class();
            if ($this->isNewRecord) {
                $ret = $manager->add($rule);
            } else {
                $ret =$manager->update($this->getOldAttribute('name'), $rule);
            }
        }
        return $ret;
    }

    public function addRule()
    {
        $ret = false;
        if ($this->validate()) {
            $manager = Yii::$app->getAuthManager();
            $class = $this->className;
            $rule = new $class();
            $ret = $manager->add($rule);
        }
        return $ret;

    }


    public function delete()
    {
        $manager = Yii::$app->getAuthManager();
        $class = $this->className;
        $rule = new $class();
        $ret = $manager->remove($rule);
        return $ret;
    }

    public static function getRulesClasses()
    {
        $pathToFile = \Yii::getAlias('@app/modules/adminx/components/rules');
        $namespace = 'app\modules\adminx\components\rules';

        $ret = [];
        if ($handle = opendir($pathToFile)) {
            while (false !== ($file = readdir($handle))) {
                if (strpos($file, ".php")){
                    $ruleClass = $namespace . '\\' . str_replace('.php', '', $file);
                    $ret[$ruleClass] = $ruleClass;
                }
            }
            closedir($handle);
        }
        return $ret;
    }


}
