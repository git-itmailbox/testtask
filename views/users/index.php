<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\bootstrap;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;


function dltButton($key){

    return (Html::a("delete",'#' , ['data-id'=>$key,
        'class'=>'dltbtn btn btn-danger'
    ]));
}


function editButton($key){

    return (Html::a("edit", '#' , ['data-id'=>$key,
        'class'=>'editbtn btn btn-default',
        'data-toggle' => 'modal',
        'data-target' => '#myModal',
    ]));
}



?>
<div class="row">
    <div class="col-md-2 col-md-offset-1"><h1>Users</h1></div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-1" id="tableUsers">
<?php Pjax::begin(['id'=>'pjaxupd', 'linkSelector'=>['#pjaxupd','.dltbtn'], 'enablePushState' => false]) ?>
        <?= GridView::widget([
            'dataProvider' => $users,
            'id' =>'pjaxupd',
//            'pjax'=>true,
            'headerRowOptions' => ['style' => 'background-color:gray'],
            'columns' => [
                ['attribute' => 'name',
                    'value' => 'name',
                    'label' => 'User name'],
                'email',
                ['attribute' => 'company',
                    'value' => 'company.name'],
                [
                    'label' => 'action',
                    'value' => function($model, $key, $index, $grid){
                        return editButton($model['id']) . dltButton($model['id']);
                    },
                    'format' => 'raw'

                    ]

            ],

//            ['class'=>'yii\grid\ActionColumn', 'template' => '{delete}'],
        ]);
        ?>
        <?php Pjax::end() ?>

    </div>
</div>
<div class="col-md-10 col-md-offset-1">


<!--    <td><a href="#" class="btn btn-default">edit</a></td>-->
<!--    <td><a href="#" class="btn btn-danger">remove</a></td>-->

    <a href="#myModal" id="addBtn" data-toggle="modal" data-target="#myModal" class="editbtn btn btn-success">add</a>

</div>

<div id="myModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Заголовок модального окна -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add new User</h4>
            </div>
            <!-- Основное содержимое модального окна -->
            <div class="modal-body">
                <div class="row">

                <input id="isedit" type="hidden">

                <div class="col-md-3">
                    <label for="name">Name</label>
                    <input id="name" type="text">
                </div>
                <div class="col-md-3">
                    <label for="email">Email</label>
                    <input id="email" type="text">
                </div>
                <div class="col-md-3">
                    <label for="company_id">Company</label>
                    <select id="company_id" >
                        <option value="0"></option>


<?php   foreach ($companies as $company){?>
                    <option value="<?= $company['id'] ?>"><?= $company['name'] ?> </option>
<?php
                } ?>

                </select>
                </div>
                </div>

            </div>
            <!-- Футер модального окна -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="sbmtFormUsers" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
