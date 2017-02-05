<?php
use yii\helpers\Html;
?>


    <div class="col-md-1">
        <?= Html::encode($model['name']) ?>
    </div>

    <div class="col-md-2">
        <?= Html::encode($model['date']) ?>
    </div>

    <div class="col-md-4">
        <?= Html::encode($model['resource']) ?>
    </div>

    <div class="col-md-1">
        <?= Html::encode($model['transferred']) ?>
    </div>
