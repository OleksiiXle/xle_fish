<?php if (!empty($userStyles)):?>
    <style>
        .listItem{
            <?php foreach ($userStyles['listItem'] as $key => $value):?>
                <?=$key . ': ' . $value . ';'?>
            <?php endforeach;?>
        }
        .itemsArea{
            <?php foreach ($userStyles['itemsArea'] as $key => $value):?>
                <?=$key . ': ' . $value . ';'?>
            <?php endforeach;?>
        }
    </style>
<?php endif;?>


<?php
$this->registerJs("
   var _selectedItem = '$selectedItem';
   var _selectedText = '{$listData[$selectedItem]}';
  ",\yii\web\View::POS_HEAD);
if (!empty($jsFunction)){
    $this->registerJs("$jsFunction",\yii\web\View::POS_HEAD);
}
?>

<div id="topItem" >
    <div id="selectedItem" class="listItem">
        <?=$listData[$selectedItem]?>
    </div>
    <div id="items" class="itemsArea" style="display: none">
            <?php foreach ($listData as $key => $value):?>
                <?php $disp = ($key != $selectedItem) ? 'block' : 'none'?>
                <div id="itemArea_<?=$key?>" class="itemArea" style="display: <?=$disp?>">
                    <a id="<?=$key?>" class="listItem btnItem"  = ><?= $value?></a>
                </div>
            <?php endforeach;?>
    </div>
</div>
