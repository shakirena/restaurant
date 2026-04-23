    <?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use app\models\Store;
    use app\models\Arrival;
use app\models\Contractor;
use app\models\Menu;
use kartik\select2\Select2;
use app\models\TypeProduct;



/* @var $this yii\web\View */
/* @var $searchModel app\models\ArrivalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Arrivals';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="arrival-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="btn-group">
        <?= Html::input("text",'barcode','',['id'=>'barcode','size'=>'10', 'class' => 'form-control','onChange' => 'addSellBarcode($("#barcode").val())'])?>
    </div>
    <div class="btn-group">
        <?= Html::button('<i class="glyphicon glyphicon-search"></i>Axtarış', ['value' => Url::to(['arrival/find']), 'class' => 'btn btn-warning', 'id' => 'modalButton']) ?>
    </div>

    <div class="btn-group">
        <?= Html::button('<i class="glyphicon glyphicon-time"></i> Gözləmədə', ['value' => Url::to(['arrival/postponed']), 'class' => 'btn btn-info', 'id' => 'postponed_dialog']) ?>

    </div>
<br><br>
    <?php
    Modal::begin([
       // 'header' => '<h4>Find device</h4>',
        'options' => [
            'id' => 'kartik-modal2',
            'tabindex' => '-1',
        ],

        'size' => 'modal-lg',

    ]);

    echo '<div id="modalContent"></div>';

    Modal::end();
    ?>

    <?php
    Modal::begin([
        // 'header' => '<h4>Find device</h4>',
        'options' => [
            'id' => 'current',
            'tabindex' => '-1',
            'data-backdrop-limit'=>'1'
        ],

        'size' => "250px",

    ]);

    echo '<div id="modalContent1">'.  Html::input('hidden','id','',[' class' =>"form-control", 'id' =>'id'])
        .'
<div class="form-horizontal" role="form">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Miqdar</label>
    <div class="col-sm-10">'. Html::input('text','quantity','1',[' class' =>"form-control", 'id' =>'quantity']).'</div>
  </div>

  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Gəliş qiyməti (AZN)</label>
    <div class="col-sm-10">'. Html::input('text','azd','1',[' class' =>"form-control", 'id' =>'price','onchange' => 'editPriceAr()']).'</div>
  </div>

</div>'.Html::button('<i class="glyphicon glyphicon-ok"></i>  OK', ['class' => 'btn btn-success', 'onclick' => 'addArrivalReceived($("#quantity").val(),$("#price").val(),$("#id").val())']).'</div>';

    Modal::end();
    $i=0;
    ?>

    

    <?php Pjax::begin(['id' => 'grid-arrival']) ?>
    <div style= 'float:left;width:800px'>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'captionOptions'=>['encode' => false],
        'tableOptions' => [

            'class' => 'table-rena',

        ],
        'rowOptions' =>
        [
            'class' =>'myy'
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

          //  'id',
           // 'id_user',

            [
                'attribute' =>'id_product',
                'label' =>'Malın adı',
                'value' => 'idProduct.name',
                'width' => '350px',


            ],
            [
                'attribute' => 'quantity',
                'label' =>'Miqdar',
                'format' => 'raw',
                'value' => function ($model, $index, $widget)  use (&$j) {
                    $j++;
                    return Html::input('text', 'quantity[]', $model->quantity, ['class' => 'form-control input-sm', 'size' => '3', 'onChange' => "editQuantity($model->id,this.value)"]);
                }
            ],
          
            [
                'attribute' => 'price',
                'format' => 'raw',
                'label' =>'Gəliş ',
                'width' =>'75px',
                'encodeLabel' => false,

                'value' => function ($model, $index, $widget)  use (&$j) {
                   
                    return Html::input('text', 'price[]', $model->price, ['class' => 'form-control input-sm', 'size' => '3','id'=>"price".$j, 'onChange' => "editPrice($model->id,this.value)"]);
                }
            ],
            [
                'attribute' =>  'sum',

                'label' => 'Cəmi <br>qiyməti ',
                'encodeLabel' => false,
                'contentOptions' =>  function ($model, $index, $widget)    {
                    return  ['id' => 'sum'.$model->id];
                  },

            ],
            ['class' => 'kartik\grid\ActionColumn', 'template' => '{delete}',
            'buttons' => [
                'delete' =>function ($url, $model) {
                    return Html::button('<i class="glyphicon glyphicon-delete"></i>Silmək',['onclick'=>" deleteArrival($model->id)"]);}
            ],

            ],

        ],
    ]); ?>
</div>
<div  class='arrivalBraches'>


<? if ($model->id_store)  $store=$model->id_store;
else $store = 1;?>
    <?=Html::img('../img/storage.png')." Anbar". Select2::widget([
        'data' => ArrayHelper::map(Store::find()->all(), 'id', 'name'),
        'name' => 'store',
		'value'=>$store,
        'options' => [
            'placeholder' => 'Seçin',
			
            'id'=>'store8',

        ],
		
    ]); ?>
    <?=Html::img('../img/contractor.png')." Şirkət". Select2::widget([
        'data' =>  ArrayHelper::map(Contractor::find()->andWhere("id>=1")->all(), 'id', 'name'),
        'name' => 'contractor',
        'options' => [
            'placeholder' => 'Seçin',

            'id'=>'contractor',

        ]
    ]); ?>
    <br>
    <?php Pjax::begin(['id' => 'grid-update']) ?>
         <p style="color:red;font-size: 30px; padding-left:10px" > <?= round($sum,2)." AZN"?></p>
         <?php Pjax::end(); ?>
    <?=Html::img('../img/calendar.png')." Date" .
    DatePicker::widget([
        'name' => 'check_issue_date',
        'id' => 'date',
        'value' => date('Y-m-d'),
        'options' => ['placeholder' => 'Select issue date ...'],
        'type' => DatePicker::TYPE_INPUT,
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => false
        ]
    ]); ?>

    <br>

    <?= Html::button('<i class="glyphicon glyphicon-ok"></i>  OK', ['class' => 'btn btn-success', 'onclick' => 'receivedArrival($("#store8").val(),$("#contractor").val(),$("#date").val())']); ?>
    <?= Html::button('<i class="glyphicon glyphicon-time"></i>  Gözləmə', ['class' => 'btn btn-info', 'onclick' => 'postponedArrival($("#store8").val(),$("#contractor").val())']); ?>

    <?= Html::button('<i class="glyphicon glyphicon-remove"></i> Hamsını sil', ['class' => 'btn btn-danger', 'onclick' => 'deleteAll()']); ?>
</div>
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