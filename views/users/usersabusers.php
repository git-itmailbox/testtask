<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\bootstrap;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

?>

<div class="col-md-10 col-md-offset-1">
    <h1>Transfers log</h1>
    <?= GridView::widget([
        'dataProvider'=> $users,
]);

//var_dump($users);
?>


</div>