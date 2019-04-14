<?php

namespace app\components\conservation;

use Yii;
use yii\base\BaseObject;
use yii\data\Pagination;
use yii\web\Link;
use yii\web\Linkable;
use yii\web\Request;

class PaginationConserve extends Pagination
{
    public $startPage = 1;
    public $conserveName;

    /**
     * Returns the value of the specified query parameter.
     * This method returns the named parameter value from [[params]]. Null is returned if the value does not exist.
     * @param string $name the parameter name
     * @param string $defaultValue the value to be returned when the specified parameter does not exist in [[params]].
     * @return string the parameter value
     */
    protected function getQueryParam($name, $defaultValue = null)
    {
        if (($params = $this->params) === null) {
            $request = Yii::$app->getRequest();
            if ($name == $this->pageParam){
                $buf = $request->getQueryParams();
                if (!isset($buf[$name])){
                    $params[$name] = $this->startPage + 1;
                } else {
                    $params = $request instanceof Request ? $request->getQueryParams() : [];
                }
            } else {
                $params = $request instanceof Request ? $request->getQueryParams() : [];
            }
        }
        $result = isset($params[$name]) && is_scalar($params[$name]) ? $params[$name] : $defaultValue;

        return $result;
    }


}
