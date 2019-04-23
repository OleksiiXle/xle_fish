<?php
use \yii\helpers\Html;
?>

<?php
//echo $html;
//echo var_dump($tree);

$_tree = \yii\helpers\Json::htmlEncode($tree);
$this->registerJs("
    var _tree = {$_tree};
",\yii\web\View::POS_HEAD);
\app\components\widgets\menuG\MenuGAssets::register($this);

?>
<style>
    .bbb{
        background-color: aliceblue;
    }
</style>
<div >
    <div style="float: left; margin: 100px">content
        <div class="bbb">
            gfhfgh
        </div>
    </div>
    <div style="float: left; margin: 100px">content</div>
    <div style="float: left; margin: 100px">content++</div>
</div>
