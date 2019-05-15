<?php


namespace app\models;

use Yii;
use yii\base\Exception;

class MainModel extends \yii\db\ActiveRecord
{
    private $_created;
    private $_updated;


    public function beforeValidate() {
        if ($this->isNewRecord){
            $this->created_at = time();
            $this->updated_at = time();
        }
        $this->updated_at = time();
        return parent::beforeValidate();
    }

    public function getErrorsWithAttributesLabels()
    {
        $errorsArray = $this->getErrors();
        $ret = [];
        foreach ($errorsArray as $attributeName => $attributeErrors ){
            foreach ($attributeErrors as $attributeError)
            $ret[$this->getAttributeLabel($attributeName)] = $attributeError;
        }
        return $ret;
    }

    public function showErrors()
    {
        $ret = $lines = '';
        $header = '<p>' . Yii::t('yii', 'Please fix the following errors:') . '</p>';
        $errorsArray = $this->getErrorsWithAttributesLabels();
        foreach ($errorsArray as $attrName => $errorMessage){
            $lines .= "<li>$attrName : $errorMessage</li>";
        }
        if (!empty($lines)) {
            $ret = "<div>$header<ul>$lines</ul></div>" ;
        }

        return $ret;

    }

    public function validateNotEmpty($attribute, $params)
    {
        if (empty($this->$attribute)) {
            $this->addError($attribute, 'Необхідно заповнити ' . $this->attributeLabels()[$attribute]);
        }
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        $this->_created = '';
        if (isset($this->created_at)){
            $this->_created = Functions::intToDateTime($this->created_at);
        }
        return $this->_created;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        $this->_updated = '';
        if (isset($this->updated_at)){
            $this->_updated = Functions::intToDateTime($this->updated_at);
        }
        return $this->_updated;
    }




}
