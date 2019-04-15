<?php
//\app\components\widgets\menuUpdate\MenuUpdateAssets::register($this);

?>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <b>
                <?php
                echo \app\components\widgets\menuUpdate\MenuUpdateWidget::widget([
                    'menu_id' => 'NumberOne'
                ])
                ?>;
            </b>
        </div>
        <div class="col-md-6">
            <b>
                <?php
                echo \app\components\widgets\menuUpdate\MenuUpdateWidget::widget([
                    'menu_id' => 'NumberTwo'
                ])
                ?>;
            </b>

        </div>
    </div>
</div>

<script>
    $(document).ready ( function(){
        alert('lokoko');
        initTrees();
    });

</script>

