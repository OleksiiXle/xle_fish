<?php
//\app\components\widgets\menuUpdate\MenuUpdateAssets::register($this);

?>
<div class="container">
    <div class="row">
        <div class="col-md-6" style="background-color: aliceblue">
            <b>
                <?php
                echo \app\components\widgets\menuUpdate\MenuUpdateWidget::widget([
                    'menu_id' => 'NumberOne',
                    'params' => [
                            'mode' => 'update'
                    ]
                ])
                ?>;
            </b>
        </div>
        <div class="col-md-6">
            <div id="menuInfo">

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready ( function(){
        initTrees();
    });

</script>

