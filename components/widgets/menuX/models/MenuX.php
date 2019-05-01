<?php

namespace app\components\widgets\menuX\models;

use yii\base\Exception;
use yii\helpers\Html;
use yii\helpers\Url;

class MenuX extends \yii\db\ActiveRecord{
    //-- $query->createCommand()->getSql()

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_x';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'name', 'route', 'role'], 'required'],
            [['name', 'route', 'role'], 'string', 'min' => 3, 'max' => 255],
            [['parent_id', 'sort'], 'integer'],

            [['node1' , 'node2' , 'nodeAction'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => \Yii::t('app', 'Предок'),
            'sort' => \Yii::t('app', 'Сортировка'),
            'name' => \Yii::t('app', 'Название'),
            'route' => \Yii::t('app', 'Маршрут'),
            'role' => \Yii::t('app', 'Роль'),
        ];
    }


    public function getChildren() {
        return $this->hasMany(self::className(), ['parent_id' => 'id']);
    }

    //*****************************************    ПЕРЕОПРЕДЕЛЕННЫЕ МЕТОДЫ   ***************************
    public function beforeSave($insert) {
        if ($insert){
            //-- определение сортировки
            $maxSort = self::find()->where(['parent_id' => $this->parent_id])->max('sort');
            $this->sort = (isset($maxSort)) ? ($maxSort +1) : 1;
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * Возаращает массив прямых потомков для вывода их при раскрытии узла дерева
     * @param int $parent_id
     * @param null $level
     * @return array
     */
    public static function getMenuArray($parent_id = 0) {
        $res = [];
        $tree = self::find()->andWhere(['parent_id' => $parent_id])->orderBy('sort')->all();
        foreach ($tree as $d){
            $res[] = [
                'id'            => $d->id,
                'parent_id'     => $d->parent_id,
                'name'          => \Yii::t('app', $d->name),
                'hasChildren'   => (count($d->children) > 0),
            ];
        }
        return $res;
    }

    /**
     * Возаращает массив идентификаторов предков плюс свой ид
     * @param $id
     * @return array
     */
    public static function getParentsIds($id){
        $parents=[$id];
        $node = self::findOne($id);
        if (isset($node)){
            $pid = $node->parent_id;
            do{
                $parent = self::findOne($pid);
                if (isset($parent)){
                    $parents[] = $parent->id;
                    $pid = $parent->parent_id;
                }
            } while($parent != null);
        }
        return $parents;
    }

    /**
     * Записывает в массив $target идентификаторы всех потомков
     * @param $parent_id
     * @param $target
     * @return bool
     */
    public static function getChildrenArray($parent_id, &$target)
    {
        //--
        $children = self::find()
            ->where(['parent_id' => $parent_id])
        //    ->asArray()
            ->all();
        if (count($children) > 0) {
            foreach ($children as $child) {
                $target[]=  [
                    'id' => $child['id'],
                    'parent_id' => $child['parent_id'],
                    'name' => \Yii::t('app', $child['name']),
                    'hasChildren'   => (count($child->children) > 0),
                ];
                self::getChildrenArray($child['id'], $target);
            }
            return true;
        }
    }



    /**
     * Возвращает строку с ид потомков
     * @param $tree - массив дерева типа self::find()->asArray()->all();
     * @param $parent_id - родительский элемент
     * @return string - строка через запятую с ИД его потомков
     */
    public static function getAllChildren($tree, $parent_id){
        $html = '';
        foreach ($tree as $row){
            if ($row['parent_id'] == $parent_id) {
                $html .= $row['id'] ;
                $html .=  ', ' . self::getAllChildren($tree, $row['id']);
            }
        }
        return $html;
    }


    /**
     * Возвращает строку с деревом
     * @param $tree - полный массив дерева
     * @param $pid - корень
     * @return string
     */
    public static function getTree($tree, $pid){
        $html = '';
        foreach ($tree as $row) {
            if ($row['parent_id'] == $pid) {
                if ($pid > 0){
                    $hasChildren = self::find()->where(['parent_id' => $row['id']])->count();
                    if ($hasChildren){
                        $content = '<a class="node" '
                            . ' onclick="clickAction(this);"'
                            . '> ' . \Yii::t('app', $row['name'])
                            . '</a>';
                    } else {
                        $content = Html::a(\Yii::t('app', $row['name']), Url::to($row['route'], true),
                            [
                                'class' => 'route',
                            ]);
                    }
                    $html .= '<li>'
                        . $content
                        . self::getTree($tree, $row['id'])
                        . '</li>';

                } else{
                    $html .= self::getTree($tree, $row['id']);

                }
            }
        }
        return $html ? '<ul class="ulMenuX" style="padding-left: 15px">' . $html . '</ul>' : '';
    }

    /**
     * Записывает в массив $target идентификаторы всех потомков
     * @param $parent_id
     * @param $target
     * @return bool
     */
    public static function getIds($parent_id, &$target)
    {
        //--
        $children = self::find()
            ->select(['id', 'parent_id'])
            ->where(['parent_id' => $parent_id])
            ->asArray()
            ->all();
        if (count($children) > 0) {
            foreach ($children as $child) {
                $target[]=  $child['id'];
                self::getIds($child['id'], $target);
            }
            return true;
        }
    }

}
