<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\bootstrap;
use yii\widgets\Pjax;
use yii\grid\GridView;

$faker = Faker\Factory::create();

function dltButton($key){

    return (Html::a("delete",'#' , ['data-id'=>$key,
        'class'=>'dltCompanyBtn btn btn-danger'
    ]));
}


function editButton($key){

    return (Html::a("edit", '#',['data-id'=>$key,
        'class'=>'editcompany btn btn-default',
        'data-toggle' => 'modal',
        'data-target' => '#myModal',
    ]));
}


?>

<div class="col-md-6 col-md-offset-1">
    <h1>Companies</h1>

    <?php Pjax::begin(['id'=>'pjaxupd', 'linkSelector'=>['#pjaxupd .dltbtn .dltCompanyBtn' ], 'enablePushState' => false]) ?>
    <?= GridView::widget([
        'dataProvider' => $companies,
        'id' =>'pjaxupd',
        'headerRowOptions' => ['style' => 'background-color:gray'],
        'columns' => [
            ['attribute' => 'name',
                'value' => 'name',
            ],
            ['attribute' => 'quota',
                'value' => 'quota',
            ],
            [
                'label' => 'action',
                'value' => function($model, $key, $index, $grid){
                    return editButton($model['id']) . dltButton($model['id']);
                },
                'format' => 'raw'

            ]

        ],
    ]);
    ?>
    <?php Pjax::end() ?>

    <a href="#" id="addBtn" class="btn btn-success editcompany" data-toggle = 'modal' data-target='#myModal'>add</a>

</div>

<div id="myModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Заголовок модального окна -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add new Company</h4>
            </div>
            <!-- Основное содержимое модального окна -->
            <div class="modal-body">
                <div class="row">
                    <input id="isedit" type="hidden">

                    <div class="col-md-2">
                        <label for="name">Name</label>
                        <input class="form-control" id="name" required type="text">
                    </div>
                    <div class="col-md-2">
                        <label for="quota">Quota</label>
                        <input class="form-control" id="quota" required  type="number">
                    </div>
                    <div class="col-md-1">
                        <br><label for="quota">TB</label>

                    </div>
                </div>

            </div>
            <!-- Футер модального окна -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="sbmtFormCompanies" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
