<?php
use yii\helpers\Html;
use yii\bootstrap;
use kartik\alert\Alert;
//use yii\bootstrap\Alert;
?>
<?php
if(isset($message))
{
echo Alert::widget([
    'type' => Alert::TYPE_SUCCESS,
    'icon' => 'glyphicon glyphicon-ok-sign',
    'titleOptions' => ['icon' => 'info-sign'],
    'body' => $message,
    'showSeparator' => true,
    'delay' => 2000
]);}  ?>

<div class="row">
        <div class="col-md-6">
        </div>

        <div class="col-md-6 ">
            <h1>REPORT</h1>
            <div class="row">
                <div class="col-md-3">
                        <select name="month" id="month">
                            <?php foreach ($months as $key => $month) { ?>
                             <option value="<?= $key ?>" ><?= $month  ?></option>
                        <?php } ?> </select>
                </div>

                <div class="col-md-5 pull-left">
                    <button id="showReport" type="button" class="btn btn-success">Show report</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 pull-right"><a href="/abusers/generatedata" class="btn btn-lg btn-default">Generate data</a>
                </div>
            </div>

        </div>
    </div>

<div class="col-md-6 col-md-offset-1">
    <?php if($data) ?>

    <table id="tableReport" class="table table-condensed table-striped table-bordered table-hover">

       <thead> <tr>
            <th>name</th>
            <th>quota</th>
            <th>summary</th>
        </tr>
</thead>

    </table>
</div>


<!---->
<!--<table class="table table-bordered table-hover table-responsive table-striped">-->
<!--    <tr>-->
<!--        <th>Company</th>-->
<!--        <th>Used</th>-->
<!--        <th>Quota</th>-->
<!--    </tr>-->
<!--    --><?php //foreach ($exceededlimit as $item) {
//        ?><!--<tr>-->
<!---->
<!---->
<!--        <td> --><?//= $item['name'] ?><!--</td>-->
<!--        <td> --><?//= $item['summary'] ?><!--</td>-->
<!--        <td> --><?//= $item['quota'] ?><!--</td>-->
<!--        </tr>-->
<!---->
<!--        --><?php
//
//
//    }
//    ?>
<!---->
<!--</table>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dynatable/0.3.1/jquery.dynatable.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dynatable/0.3.1/jquery.dynatable.min.css" />