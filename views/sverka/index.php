<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\models\Store;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use app\models\Product;
use kartik\select2\Select2;
use app\models\TypeProduct;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SverkaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sverkas';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sverka-index">
<br>

    <div class='row'>
        <div class="col-md-2">
            <?= Html::dropDownList("store", Yii::$app->session->get("sverka"),ArrayHelper::map(Store::find()->all(), 'id', 'name'),["id" => 'update-store','onchange'=>'editStore($("#update-store").val())','class' => 'form-control']) ?>
        </div>


        <div class="col-md-2">
            <?= Html::button('Записать <i class="glyphicon glyphicon-play"></i>', ['class' => 'btn btn-danger', 'onclick' => 'sverkaReceived()']) ?>
        </div>
    </div>
    <br>
    <div class="btn-group" id="div1" style="width: 150px">
        <?= Select2::widget([
            'data' =>  ArrayHelper::map(TypeProduct::find()->where(['id_parent' =>0])->all(), 'id', 'name'),
            'name' => 'type1',
            'options' => [
                'placeholder' => 'Seçin',
                'onchange' => 'changeType1($("#type1").val())',
                'id'=>'type1',

            ]
        ]); ?>
    </div>
    <div class="btn-group" id="div2" style="width: 150px; display:none">
        <?= Select2::widget([
            'name' => 'type2',
            'options' => [
                'placeholder' => 'Seçin',
                'onchange' => 'changeType2($("#type2").val())',
                'id'=>'type2',


            ]
        ]); ?>
    </div>
    <div class="btn-group" id="div3" style="width: 150px;display:none">
        <?= Select2::widget([
            'name' => 'type3',
            'options' => [
                'placeholder' => 'Seçin',
                'onchange' => 'changeType3($("#type3").val())',
                'id'=>'type3',

            ]
        ]); ?>
    </div>

    <div class="btn-group" id="div4" style="width: 150px;display:none">
        <?= Select2::widget([
            'name' => 'type4',
            'options' => [
                'placeholder' => 'Seçin',
                'onchange' => 'changeType4($("#type4").val())',
                'id'=>'type4',

            ]
        ]); ?>
    </div>
    <div class="btn-group" id="div5" style="width: 150px;display:none">
        <?= Select2::widget([
            'name' => 'type5',
            'options' => [
                'placeholder' => 'Seçin',
                'onchange' => 'changeType4($("#type5").val())',
                'id'=>'type5',

            ]
        ]); ?>
    </div>
    <div class="btn-group" id="div6" style="width: 350px;display:none">
        <?= Select2::widget([
            'name' => 'product',
            'options' => [
                'placeholder' => 'Seçin',
                'id'=>'product',
                'multiple'=>true,
            ]
        ]); ?>
    </div>
    <div class="btn-group">
        <?= Html::button('<i class="glyphicon glyphicon-ok"></i>  OK', ['class' => 'btn btn-success', 'onclick' => 'addSellType($("#product").val())']); //addSellType($("#product").val())?>

    </div>


    <br><br>
    <?php Pjax::begin(['id' => 'grid-arrival']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [

            'class' => 'table-rena table-rena2',

        ],

        'showPageSummary' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],



            [
                'label' =>'Malın adı',
                'value' => 'getType',
                'pageSummary' => 'Yekun',
                'pageSummaryOptions' => ['class' => 'text-right text-warning']

            ],
            [
                'attribute' => 'quantity',
                'label' =>'Fakt.qaliq',
                'format' => 'raw',
                'width' => '50px',
                'pageSummary' =>$sum_fakt,
                'value' => function ($model, $index, $widget) {
                    return Html::input('text', 'quantity[]', $model->quantity, ['class' => 'form-control input-sm', 'style' => ' width:50px !important', 'onChange' => "editQuantity($model->id,this.value)"]);
                }
            ],
            [
                'encodeLabel' => false,
                'label' => 'Cəmi hesab-<br>lanış qalıq',
                'value' => 'quantitySklad',
                'pageSummary' => true,
            ],

            [
                'label' => 'Fərq',
                'value' => 'difference'
            ],
            [
                'encodeLabel' => false,
                'label' => 'Satış <br> qiymeti',
                'value' => 'priceSell.pricesell',

            ],
            [
                'encodeLabel' => false,
                'label' => 'Fakt. qal <br> məbləği',
                'value' => 'sum',
                'pageSummary' => true,
            ],
            [
                'encodeLabel' => false,
                'label' => 'Son aliş <br> qiymeti',
                'value' => 'priceSell.price'
            ],
            [
                'encodeLabel' => false,
                'label' => 'Fak. Son aliş <br> qiymeti',
                'value' => 'priceSellSum',
                'pageSummary' => true,
            ],
            ['class' => 'kartik\grid\ActionColumn', 'template' => '{delete}'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
<?php
$script = <<< JS
 $(document).ready(function(){
    $("#barcode").val("");
    $("#barcode").focus();
});
JS;
$this->registerJs($script);
?>