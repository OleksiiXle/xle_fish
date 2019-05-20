<?php if (!empty($userStyles)):?>
    <style>
        .listItem{
            <?php foreach ($userStyles['listItem'] as $key => $value):?>
                <?=$key . ': ' . $value . '!important;'?>
            <?php endforeach;?>
        }
        .itemsArea{
            <?php foreach ($userStyles['itemsArea'] as $key => $value):?>
                <?=$key . ': ' . $value . '!important;'?>
            <?php endforeach;?>
        }
    </style>
<?php endif;?>


<?php
$jsFunctionName = "clickFunction_" . $selectId;

//$this->registerJs(" function $jsFunctionName(item) $jsFunction",\yii\web\View::POS_HEAD);
//$this->registerJs("jQuery('#$selectId').selectXle('$selectId', '$selectedItem', '{$listData[$selectedItem]}', '$jsFunction' );");
$this->registerJs("jQuery('#$selectId').selectXle('$selectId', '$selectedItem', '$listData[$selectedItem]', '$jsFunctionBody');");

?>
<div id="<?=$selectId?>">
    <div id="topItem_<?=$selectId?>" >

        <div id="selectedItem_<?=$selectId?>" class="listItem listItem_<?=$selectId?>">
            <?=$listData[$selectedItem]?>
        </div>

        <div id="items_<?=$selectId?>" class="itemsArea itemsArea_<?=$selectId?> " style="display: none">

            <?php foreach ($listData as $key => $value):?>

                <?php $disp = ($key != $selectedItem) ? 'block' : 'none'?>
                <div id="itemArea_<?=$selectId?>_<?=$key?>" class="itemArea itemArea_<?=$selectId?>" style="display: <?=$disp?>">
                    <a
                            id="<?=$selectId?>_<?=$key?>"
                            class="listItem listItem_<?=$selectId?> btnItem"
                            data-id = "<?=$key?>"
                    ><?= $value?></a>
                </div>

            <?php endforeach;?>
        </div>
    </div>
</div>
