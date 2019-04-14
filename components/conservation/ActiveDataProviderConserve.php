<?php
/**
 * Created by PhpStorm.
 * User: chameleon
 * Date: 26.10.17
 * Time: 15:33
 */

namespace app\components\conservation;


use yii\data\ActiveDataProvider;

class ActiveDataProviderConserve extends ActiveDataProvider
{
    public $conserveName;
    public $conserves = [];
    public $pageSize = 10;
    public $startPage =1;
    public $baseModel; //-- объект основной модели данных
    public $filterModelClass; //-- класс модели фильтра
    public $filterModel; //-- модель фильтра
    public $hasFilter=false;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->getConserves();
        $this->pagination = [
            'class'            => 'app\components\conservation\PaginationConserve',
            'conserveName' => $this->conserveName,
            'pageSize' => $this->pageSize,
            'startPage' => $this->conserves['startPage'],
            'totalCount' => 0,
        ];
        //-- фильтр
        if (isset($this->filterModelClass)){
            $params = [];
            if (!$this->filterModel){
                $this->filterModel = new $this->filterModelClass;
            }

            if (\Yii::$app->request->isPost){ //-- пришел новый фильтр
                $params = \Yii::$app->request->post();
                $this->filterModel->load($params);
                $cJSON = \Yii::$app->conservation->setConserveGridDB($this->conserveName, 'filter', json_encode($this->filterModel->getAttributes()));
                $cJSON = \Yii::$app->conservation->setConserveGridDB($this->conserveName, $this->pagination->pageParam, 1);
                $this->pagination->startPage = 0;
            } elseif (isset($this->conserves['filter'])){ //-- фильтр не пришел, но может быть что нибудь есть в консерве с прошлого раза
                $params = (array) $this->conserves['filter'];
                $this->filterModel->setAttributes($params);
            }
          //  \Yii::trace(\yii\helpers\VarDumper::dumpAsString($this->filterModel), 'dbg');
            $this->query = $this->filterModel->getQuery();
         //   \Yii::trace(\yii\helpers\VarDumper::dumpAsString($this->query), 'dbg');

        } else {
            $this->query = $this->baseModel;

        }


    }

    public function getConserves(){
        $buf = \Yii::$app->conservation->getConserveGridDB($this->conserveName);
        $this->conserves['startPage'] = (isset($buf['data']['page'])) ? $buf['data']['page'] : $this->startPage;
        $this->conserves['sort'] = (isset($buf['data']['sort'])) ? $buf['data']['sort'] : null;
        $this->conserves['filter'] = (isset($buf['data']['filter'])) ? json_decode($buf['data']['filter']) : null;

    }

}