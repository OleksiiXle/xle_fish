<?php

namespace app\models;

use yii\base\Exception;
use yii\helpers\FileHelper;


class Functions
{
    public static $rightArray=[
        0 => 'a_main_code',
        1 => '',
        2 => '',
        3 => '',
        4 => '',
        5 => '',
        6 => '',
        7 => '',
    ];
    public static $exelHeader = [
        1 => 'A',
        2 => 'B',
        3 => 'C',
        4 => 'D',
        5 => 'E',
        6 => 'F',
        7 => 'G',
        8 => 'H',
        9 => 'I',
        10 => 'J',
        11=> 'K',
        12=> 'L',
        13=> 'M',
        14=> 'N',
        15=> 'O',
    ];



    public static function intToDate($i){
        $res =  (isset($i) && is_numeric($i) && ($i>0)) ? date('d.m.Y',  $i) : '';
        return $res;
    }

    public static function dateToInt($d){
        if (isset($d) && is_string($d)){
            if ($d == ''){
                return null;
            }
            $arr = date_parse($d);
            $res = mktime(0, 0, 0,  $arr['month'],$arr['day'], $arr['year']);
            return $res;
        } else
            return null;
    }

    public static function dateTimeToInt($d){
        if (isset($d) && is_string($d)){
            if ($d == ''){
                return null;
            }
            $arr = date_parse($d);
            $res = mktime($arr['hour'], $arr['minute'], $arr['second'],  $arr['month'],$arr['day'], $arr['year']);
            return $res;
        } else
            return null;
    }

    public static function intToDateTime($i){
        $res =  (isset($i) && is_numeric($i)) ? date('d.m.Y H:i',  $i) : '';
        return $res;
    }






    //*************************** CVS *****************************************************************
    /**
     * Вывод трех мерного ассоциативного массива в CSV файл
     * - ключи первого подмассива будут в превом ряду
     * @param $data - массив
     * @param $pathToFile
     * @param $department_id
     * @param string $title
     * @return mixed
     */
    public static function exportToCSV($data, $pathToFile, $fileMask = 'report'){
        try{
            $user = \Yii::$app->user->getId();
            $fileName = $pathToFile . '/' . $fileMask . '_' . $user . '.csv';
            $fp = fopen($fileName, 'w');

            $headerArr = array_keys($data[0]);
            fputcsv($fp, $headerArr);
            foreach ($data as $fields) {
                fputcsv($fp, $fields);
            }
            fclose($fp);
            return 'o.k.';
        } catch (Exception $e){
            return $e->getMessage();
        }

    }


    /**
     * Чтение CSV файла в массив, возвращает массив
     * @param $fileName
     * @return array|string
     */
    public static function readCSV_ToArray($fileName){
        if (!file_exists($fileName) ) {
            return 'file not found ' . $fileName;
        }
        if (!is_readable($fileName)) {
            return 'file not is_readable ' . $fileName;
        }
        $data = [];
        if (($handle = fopen($fileName, 'r')) !== false) {
            $colName = fgetcsv($handle, 1000, ',');
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $buf = [];
                for ($i=0; $i < count($row); $i++){
                    $buf[$colName[$i]]=$row[$i];
                }
                if (!is_numeric($buf['i_positions_amount'])){
                    $buf['i_positions_amount'] = 0;
                }
                $data[]= $buf;
            }
            fclose($handle);
            return $data;
        } else {
            return 'file not fgetcsv ' . $fileName;
        }
    }

    public static function addCSV_str($csvStr, $root_id, $staff_order_id, $error_id ){
        $errArr = '';
        try{
            $prn = strval($csvStr['y_prn']);
            $cat_type = iconv_substr ($csvStr['a_main_code'], 0 , 1 , 'UTF-8' );
            switch ($cat_type){
                case 'н': //*********************************************   ПОДРАЗДЕЛЕНИЕ
                    //   echo 'department ' .  $csvStr['g_name'] . '<br>';
                    $department = new OrderProjectDepartment();
                    $department->name = $csvStr['g_name'];
                    $department->x_rn = $csvStr['x_rn'];
                    $department->staff_order_id = $staff_order_id;

                    if (trim($csvStr['z_level']) == '') {
                       // echo '   root' . '<br>';
                        $department->parent_id = $parent_id = $root_id;
                        $department->setAttributes($csvStr);
                    } else {
                        $parent = OrderProjectDepartment::find()
                            ->andWhere(['x_rn' => $prn, 'staff_order_id' => $staff_order_id])
                            ->asArray()->all();
                        if (count($parent) != 1) {
                            $errArr = $cat_type . '     Error - count=' . count($parent) .  ' name=' . $csvStr['g_name'] .
                                ' - not found RN=' . $prn . '<br>';
                            $department->parent_id = $parent_id = $error_id;
                        } else {
                            $department->parent_id = $parent_id = $parent[0]['id'];
                        }
                    }
                    //----------- словари
                    $start = stripos($csvStr['b_department_code'],'<g>')+3;
                    $end = stripos($csvStr['b_department_code'],'</g>');
                    $globalCode = substr($csvStr['b_department_code'], $start, ($end - $start));
                    if (($end - $start) > 0){
                        //   echo   $globalCode . '  ->  '  . $csvStr['b_department_code'] . PHP_EOL;
                        $department->global_code = $globalCode;
                    }
                    $start = stripos($csvStr['b_department_code'],'<b>')+3;
                    $end = stripos($csvStr['b_department_code'],'</b>');
                    $subordination = substr($csvStr['b_department_code'], $start, ($end - $start));
                    if (($end - $start) > 0){
                        //   echo   $subordination . '  ->  '  . $csvStr['b_department_code'] . PHP_EOL;
                        $department->subordination = $subordination;
                    }
                    $department->save();
                    if (!$department->save()) {
                        $errArr = 'Ошибка сохранения подразделения : ';
                        foreach ($department->getErrors() as $key => $value){
                            $errArr .=  $key . ': ' . $value[0] . ' *** ';
                        }
                    };
                    break;
                case 'п':  //********************************************** ПОСАДА
                    //  echo 'positiom ' .  $csvStr['g_name'] . '<br>';
                    $newPosition = new OrderProjectPosition();
                    $newPosition->name = $csvStr['g_name'];
                    $newPosition->staff_order_id = $staff_order_id;
                    $parent = OrderProjectDepartment::find()
                        ->andWhere(['x_rn' =>  $prn, 'staff_order_id' => $staff_order_id])
                        ->asArray()->all();
                    if (count($parent) !=1){
                        $errArr =
                            $cat_type . '     Error - count=' . count($parent) . ' name=' . $csvStr['g_name'] .
                            ' - not found RN=' . $prn ;
                    } else {
                        if (count($parent) == 0){
                            $errArr = 'Должности : ' . $csvStr['g_name'] . ' не найдено подразделение - prn=' . $prn;
                            break;
                            }
                        $newPosition->order_project_department_id = $parent[0]['id'];
                        $newPosition->setAttributes($csvStr);
                        //****** словарные
                        $start = stripos($csvStr['b_department_code'],'<g>')+3;
                        $end = stripos($csvStr['b_department_code'],'</g>');
                        $globalCode = substr($csvStr['b_department_code'], $start, ($end - $start));
                        if (($end - $start) > 0){
                            //   echo   $globalCode . '  ->  '  . $csvStr['b_department_code'] . PHP_EOL;
                            $newPosition->global_code = $globalCode;
                        }
                        //*********
                        $category_personal = iconv_substr ($csvStr['a_main_code'], 1 , 2 , 'UTF-8' );
                        switch ($category_personal){
                            case "нс":
                                $newPosition->position_category = 1;
                                break;
                            case "мс":
                                $newPosition->position_category = 2;
                                break;
                            case "цд":
                                $newPosition->position_category = 3;
                                break;
                            case "цп":
                                $newPosition->position_category = 4;
                                break;
                            default:
                                $newPosition->position_category = 99;
                                break;
                        }
                        $group = iconv_substr ($csvStr['a_main_code'], 3 , 2 , 'UTF-8' );
                        switch ($group){
                            case "ко":
                                $newPosition->position_group = 1;
                                break;
                            case "кр":
                                $newPosition->position_group = 2;
                                break;
                            case "кс":
                                $newPosition->position_group = 3;
                                break;
                            case "кз":
                                $newPosition->position_group = 4;
                                break;
                            case "кв":
                                $newPosition->position_group = 5;
                                break;
                            case "кх":
                                $newPosition->position_group = 6;
                                break;
                            case " г?":
                                $newPosition->position_group = 7;
                                break;
                            case "c?":
                                $newPosition->position_group = 8;
                                break;
                            case "в?":
                                $newPosition->position_group = 9;
                                break;
                            case "і?":
                                $newPosition->position_group = 10;
                                break;
                            default:
                                $newPosition->position_group = 99;
                                break;
                        }
                        $attestation_to_civil = iconv_substr ($csvStr['a_main_code'], 5 , 1 , 'UTF-8' );
                        switch ($attestation_to_civil){
                            case "0":
                                $newPosition->attestation_to_civil = 0;
                                break;
                            case "1":
                                $newPosition->attestation_to_civil = 1;
                                break;
                            default:
                                $newPosition->position_group = 9;
                                break;
                        }
                        $financing_source = iconv_substr ($csvStr['a_main_code'], 7 , 2 , 'UTF-8' );
                        $newPosition->financing_source =  $financing_source;
                        //****
                    }
                    if (!$newPosition->save()){
                        $errArr =  ' Помилка збереження посади: ' . $csvStr['g_name'] . ' prn=' . $prn;
                        foreach ($newPosition->getErrors() as $key => $value) {
                            $errArr .=  $key . ': ' . $value[0] . ' *** ';
                        }
                    }
                    break;
                case 'и':{
                    $departmentTarget = OrderProjectDepartment::find()
                        ->andWhere(['x_rn' => $csvStr['y_prn'], 'staff_order_id' => $staff_order_id ])->one();
                    if (!isset($departmentTarget)){
                        $errArr =  $cat_type . ' rn=' . $csvStr['x_rn'] . ' prn=' . $csvStr['y_prn'] . ' *** SUMMARY NOT FOUND ' .
                            $csvStr['g_name'];

                    } else {
                        $departmentTarget->summary_txt = $csvStr['g_name'];
                        $departmentTarget->summary_amount = $csvStr['i_positions_amount'];
                        if ($departmentTarget->save()){
                        } else {
                            $errArr =  ' Помилка збереження ВСЬОГО : ';
                            foreach ($departmentTarget->getErrors() as $key => $value) {
                                $errArr .=  $key . ': ' . $value[0] . ' *** ';
                            }
                        }
                    }
                }
            }
        } catch  (Exception $e){
            $errArr = 'Ошибка загрузки ' . $e->getMessage();
            return $errArr;
        }
        return $errArr;
    }




}