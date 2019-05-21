<h1>lokoko</h1>
<?php
$listData = \app\models\Translation::LIST_LANGUAGES;
$selectedItem = $this->context->language;

?>
<select name="speed_" id="speed_" onchange="console.log(this.value);" style="color: black">
    <option value="<?=$selectedItem?>" selected="selected"><?=$listData[$selectedItem]?></option>
    <?php foreach ($listData as $key => $value):?>
        <?php if ($key != $selectedItem):?>
            <option value="<?=$key?>"><?=$listData[$key]?></option>
        <?php endif;?>
    <?php endforeach;?>
</select>

<p><select size="3" multiple name="hero[]">
        <option disabled>Выберите героя</option>
        <option value="Чебурашка">Чебурашка</option>
        <option selected value="Крокодил Гена">Крокодил Гена</option>
        <option value="Шапокляк">Шапокляк</option>
        <option value="Крыса Лариса">Крыса Лариса</option>
    </select></p>