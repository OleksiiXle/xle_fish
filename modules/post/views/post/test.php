
<?php
use yii\helpers\Html;
use yii\helpers\Url;

yii\jui\JuiAsset::register($this);
\app\modules\post\assets\UpdadePostAsset::register($this);

$this->registerJs("
 var _id = 3;
 var _cleanImage = 'xsdfsdf';
     $( function() {
        $( '#tabsl' ).tabs();
    } );

 ",\yii\web\View::POS_HEAD);

?>
<div class="container-fluid">
    <div class="row">
        <!--*************************************************************************** ЛЕВАЯ ПОЛОВИНА -->
        <div class="col-md-6"  >
            <div id="tabsl" style="height: 95vh; ">
                <!--*************************************************************************** МЕНЮ -->
                <ul>
                    <li><a href="#tabsl-1">Дерево діючої структури</a></li>
                    <li><a href="#tabsl-2">Пропозиції до наказів</a></li>
                </ul>
                <div id="tabsl-1" style="padding: 0; margin: 0">
                    <b>xfgzfg</b>
                </div>
                <div id="tabsl-2" style="padding: 0; margin: 0">
                    <b>xfgzfg</b>
                </div>
            </div>
        </div>
        <!--*************************************************************************** ПРАВАЯ ПОЛОВИНА -->
        <div class="col-md-6" >
        </div>
    </div>
</div>



<script>

    //$( "#tabsl" ).tabs();
    $(document).ready ( function(){
        /*
        $( function() {
            $( "#tabsl" ).tabs();
            //  $( "#tabsr" ).tabs();
        } );
        */
    });



</script>
