<?php

namespace app\modules\adminx\models;

use yii\base\Exception;
use yii\helpers\Html;
use yii\helpers\Url;

class MenuX extends \yii\db\ActiveRecord
{
    /**
     * @return mixed
     */
    //-- $query->createCommand()->getSql()
    const NAME_PATTERN = '/^[А-ЯІЇЄҐа-яіїєґA-Za-z ,-]+$/u'; //--маска для нимени

    //  const USER_NAME_PATTERN           = '/^[А-ЯІЇЄҐ]{1}[а-яіїєґ\']+([-]?[А-ЯІЇЄҐ]{1}[а-яіїєґ\']+)?$/u'; //--маска для нимени
    const NAME_ERROR_MESSAGE     = 'Используйте буквы и -'; //--сообщение об ошибке


    public $node1 = 0;
    public $node2 = 0;
    public $nodeAction = '';
    public $menu_id = '';
    public $result = [
        'status' => false,
        'data' => 'Some error'
    ];

    private $_childrenArray;
    private $_nodeInfo;

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
            [['parent_id', 'name'], 'required'],
            [['name', 'route', 'role', ], 'string', 'min' => 3, 'max' => 255],
            [['parent_id', 'sort', ], 'integer'],

            [['node1' , 'node2' , 'nodeAction', 'menu_id'], 'safe'],

            [['name',], 'match', 'pattern' => self::NAME_PATTERN],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => \Yii::t('app','Предок'),
            'sort' => \Yii::t('app','Сортировка'),
            'name' => \Yii::t('app','Название'),
            'route' => \Yii::t('app','Маршрут'),
            'role' => \Yii::t('app','Роль'),
        ];
    }

    //******************************************************************************* СВЯЗАННЫЕ ДАННЫЕ

    public function getChildren() {
        return $this->hasMany(self::class, ['parent_id' => 'id'])->orderBy('sort');
    }

    public function getParent() {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    //******************************************************************************* ГЕТТЕРЫ, СЕТТЕРЫ

    public function getChildrenArray()
    {
        $this->_childrenArray = [];
        foreach ($this->children as $child){
            $this->_childrenArray[] = [
                'id' => $child->id,
                'parent_id' => $child->parent_id,
                'sort' => $child->sort,
                'name' => \Yii::t('app', $child->name),
                'hasChildren'   => (count($child->children) > 0),
            ];
        }
        return $this->_childrenArray;
    }

    public function getNodeInfo()
    {
        $this->_nodeInfo = [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'sort' => $this->sort,
            'name' => \Yii::t('app', $this->name),
            'hasChildren'   => (count($this->children) > 0),
        ];
        return $this->_nodeInfo;
    }

    //*****************************************************************************    ОПЕРАЦИИ С ДЕРЕВОМ

    /**
     * Добавить потомка
     * @param $data
     * @return array|string
     */
    public function appendChild($data)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            //-- определение сортировки
            $maxSort = self::find()->where(['parent_id' => $this->id])->max('sort');

            $node = new self();
            $node->setAttributes($data);
            $node->parent_id = $data['node1'];
            $node->sort = (isset($maxSort)) ? ($maxSort +1) : 1;

            if ($node->save()){
                $this->result = [
                    'status' => true,
                    'data' => [
                        'newNode' => $node->nodeInfo,
                        'parentNode' => $node->parent->nodeInfo,
                        ]
                ];
                $transaction->commit();
                return true;
            } else {
                $this->result['data'] = $node->getErrors();
                $transaction->rollBack();
                return false;
            }
        } catch (\Exception $e) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            $this->result['data'] =$e->getMessage();
            return false;
        }
    }

    /**
     * Добавить соседа снизу в текущем уровне
     * @param $data
     * @return array|string
     */
    public function appendBrother($data)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            //-- увеличить сортировку будущих братьев на 1
            $nodesLower = self::updateAllCounters(['sort' => 1],
                'parent_id = ' . $this->parent_id . ' AND id <> ' .$this->id );
            $node = new self();
            $node->setAttributes($data);
            $node->parent_id = $this->parent_id;
            $node->sort = $this->sort + 1;
            if ($node->save()){
                $this->result = [
                    'status' => true,
                    'data' => [
                        'newNode' => $node->nodeInfo,
                        'parentNode' => $node->parent->nodeInfo,
                    ]
                ];
                $transaction->commit();
                return true;
            } else {
                $this->result['data'] = $node->getErrors();
                $transaction->rollBack();
                return false;
            }
        } catch (\Exception $e) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            $this->result['data'] =$e->getMessage();
            return false;
        }
    }

    /**
     * Поменять подразделения или должности местами в одном уровне (изменение сортировки)
     * @return boolean
     */
    public function exchangeSort($node2_id)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $node2 = self::findOne($node2_id);
            $this->sort = $node2->sort;
            $node2->sort = $this->getOldAttribute('sort');
            if (!$this->save()) {
                $transaction->rollBack();
                $this->result['data']=$this->getErrors();
                return false;
            }
            if (!$node2->save()) {
                $transaction->rollBack();
                $this->result['data']=$node2->getErrors();
                return false;
            }
            $transaction->commit();
            $this->result = [
                'status' => true,
                'data' => [
                    'node1' => $this->nodeInfo,
                    'node2' => $node2->nodeInfo,
                ]
            ];
            return true;
        } catch (\Exception $e) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            return false;
        }
    }

    /**
     * Поднять на уровень вверх - сделать меня соседом сверху моего родителя
     * @param $node2_id - ид предка
     * @return mixed
     */
    public function levelUp($node2_id)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            //-- уменьшить сортировку у своих младших братьев
            $nodesLower = self::updateAllCounters(['sort' => -1],
                'parent_id = ' . $this->parent_id . ' AND id <> ' . $this->id );
            //-- найти старого родителя
            $node2 = self::findOne($node2_id);
            $this->parent_id = $node2->parent_id;
            $this->sort = $node2->sort;
            $node2->sort = $this->getOldAttribute('sort');
            if (!$this->save()) {
                $transaction->rollBack();
                $this->result['data']=$this->getErrors();
                return false;
            }
            if (!$node2->save()) {
                $transaction->rollBack();
                $this->result['data']=$node2->getErrors();
                return false;
            }
            $transaction->commit();
            $this->result = [
                'status' => true,
                'data' => [
                    'node1' => $this->nodeInfo,
                    'node2' => $node2->nodeInfo,
                ]
            ];
            return true;
        } catch (\Exception $e) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            return false;
        }
    }

    /**
     * Опустить на уровень вниз - сделать меня потомком моего соседа сверху
     * @param $node2_id - ид соседа сверху
     * @return boolean
     */
    public function levelDown($node2_id)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            //-- увеличить сортировку у своих бывших младших братьев
            $nodesLower = self::updateAllCounters(['sort' => -1],
                'parent_id = ' . $this->parent_id  );

            //-- уменьшить сортировку у своих будующих младших братьев
            $nodesLower = self::updateAllCounters(['sort' => -1],
                'parent_id = ' . $node2_id  );

            //-- найти старого брата - нового родителя
            $node2 = self::findOne($node2_id);

            $this->parent_id = $node2_id;
            $this->sort = 1;
            if (!$this->save()) {
                $transaction->rollBack();
                $this->result['data']=$this->getErrors();
                return false;
            }
            $transaction->commit();
            $this->result = [
                'status' => true,
                'data' => [
                    'node1' => $this->nodeInfo,
                    'node2' => $node2->nodeInfo,
                ]
            ];
            return true;
        } catch (\Exception $e) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            return false;
        }
    }

    /**
     * Удалить вместе с потомками
     * @param $node1_id integer - кого удалять
     * @return array - информация для перерисовки родителя
     */
    public static function deleteWithChildren($node1_id)
    {
        $result['status'] = false;
        $result['data'] = ['error'];
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $nodeDel = self::findOne($node1_id);
            //-- увеличить сортировку у своих бывших младших братьев
            $nodesLower = self::updateAllCounters(['sort' => -1],
                'parent_id = ' . $nodeDel->parent_id);

            //-- найти родителя
            $node2 = self::findOne($nodeDel->parent_id);
            if ($nodeDel->delete() === 0) {
                $transaction->rollBack();
                return $result;
            }
            $transaction->commit();
            if (isset($node2)){
                $result = [
                    'status' => true,
                    'data' => [
                        'node1' => [],
                        'node2' => $node2->nodeInfo,
                    ]
                ];
            } else {
                $result = [
                    'status' => true,
                    'data' => [
                        'node1' => [],
                        'node2' => [],
                    ]
                ];
            }
            return $result;
        } catch (\Exception $e) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            $result['data'] = $e->getMessage();
            return $result;
        }
    }







    //*****************************************************************************    ДРУГИЕ МЕТОДЫ

    public static function getDefaultTree()
    {
        $ret = [];
        $menues = self::find()
            ->where(['parent_id' => 0])
            ->orderBy('sort')
            ->all();
        foreach ($menues as $menu){
            $ret[] = $menu->nodeInfo;
        }
        return $ret;
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
                'name'          => $d->name,
                'hasChildren'   => (count($d->children) > 0),
            ];
        }
        return $res;
    }

    /**
     * Возаращает массив прямых предков для вывода их при рисовании дефолтного дерева
     * @param $id
     * @return array
     */
    public static function getParents($id){
        $parents=[];
        $node = self::findOne($id);
        if (isset($node)){
            $pid = $node->parent_id;
            $i=0;
            do{
                $parent = self::findOne($pid);
                if (isset($parent)){
                    $parents[$i]['id'] = $parent->id;
                    $branch = Department::find()->andWhere(['parent_id' => $parent->id])->orderBy('sort')->all();
                    $res = [];
                    for ($j = 0; $j < count($branch); $j++) {
                        $res[$j]['id']            = $branch[$j]->id;
                        $res[$j]['parent_id']     = $branch[$j]->parent_id;
                        $res[$j]['name']          = $branch[$j]->name;
                        $res[$j]['hasChildren']   = (count($branch[$j]->children) > 0);
                        $res[$j]['hasAdditional'] = (count($branch[$j]->positions) > 0);
                        $res[$j]['amountInfo']    = $branch[$j]->summary_amount;
                        $res[$j]['additional']    = $branch[$j]->positions;
                    }
                    $parents[$i++]['children'] =$res ;
                    $pid = $parent->parent_id;
                }
            } while($parent != null);
        }
        return $parents;
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
    public static function getChildrenArray__($parent_id, &$target)
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
                    'name' => $child['name'],
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
                            . '> ' . $row['name']
                            . '</a>';
                    } else {
                        $content = Html::a($row['name'], Url::to($row['route'], true),
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
