<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<div class="row">
    <div class="col-md-12 s12 m7">
        <div class="card">
            <div class="card-image">
                <img src="<?=$model->mainImage?>">
                <span class="card-title"><?=Html::encode($model->name)?></span>
            </div>
            <div class="card-content">
                <p><?= HtmlPurifier::process($model->content) ?></p>
            </div>
            <div class="card-action">
                <a href="#">This is a link</a>
            </div>
        </div>
    </div>
</div>
