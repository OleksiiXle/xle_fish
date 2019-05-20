<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use \app\components\widgets\selectXle\SelectXleWidget;

?>

<div class="card">
    <div class="row">
        <div class="col-md-6">
            <div class="card-image">
                <img src="<?=$model->mainImage?>">
            </div>
        </div>
        <div class="col-md-6" align="center">
            <h4><?=Html::encode($model->name)?></h4>
        </div>
    </div>
    <div class="row">
        <div class="card-content">
            <p><?= HtmlPurifier::process($model->content) ?></p>
        </div>
        <div class="card-action">
            <div class="col-md-6">
                <b>Изображения</b>
                <?php
                $selectedItem = array_keys($model->listImages)[0];
                echo SelectXleWidget::widget([
                    'listData' => $model->listImages,
                    'selectedItem' => $selectedItem,
                    'jsFunctionBody' => "{alert(item)}",
                    'userStyles' => [
                        'listItem' => [
                            'font-weight' => 300,
                            'font-size' => 'small',
                            'color' => 'red',
                        ],
                        'itemsArea' => [
                            'background' => '#eeeeee',
                            'border' => '2px solid #bdbdbd',
                        ],
                    ],
                ]);




                // echo var_dump($model->listImages);
                ?>
            </div>
            <div class="col-md-6">
                <b>Ссылки</b>
                <?php
                echo var_dump($model->listLinks);
                ?>

            </div>
            <?php
            ?>
        </div>
    </div>
</div>
