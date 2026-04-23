<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use app\models\Client;
use app\models\Contractor;
use app\models\Spares;
use app\models\Users;
use kartik\select2\Select2;
use app\components\PushAll;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SellSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->params['breadcrumbs'][] = $this->title;
//if (  Yii::$app->user->identity->id_role==1)  $role=0;

//else $role=1;
?>
<div class="sell-index" xmlns="http://www.w3.org/1999/html">
    <br>
    <div class="noprint">

    <div class="btn-group">
    <?= Html::button('<i class="glyphicon glyphicon-search"></i>Axtarış', ['value' => Url::to(['sell/find']), 'class' => 'btn btn-info', 'id' => 'sell_dialog']) ?>
        </div>

  <!--  <div class="btn-group">
        <?= Html::button('<i class="glyphicon glyphicon-usd"></i>Borc ödənişi', ['value' => Url::to(['sell/dialog']), 'class' => 'btn btn-success', 'id' => 'dclient']) ?>

    </div>-->
    <div class="btn-group">
        <?= Html::button('<i class="glyphicon glyphicon-time"></i> Gözləmədə', ['value' => Url::to(['sell/postponed']), 'class' => 'btn btn-info', 'id' => 'postponed_dialog']) ?>

    </div>


    <div class="btn-group">
        <?= Html::button('<i class="glyphicon glyphicon-user"></i>Müştəri', ['value' => Url::to(['sell/client']), 'class' => 'btn btn-warning', 'id' => 'client_dialog']) ?>
    </div>
    <span style="margin-left:10pt;font-size: 16pt; text-decoration: double"><?= Yii::$app->session->get('client')?></span>
    </div>
    <br>

    <?php
    Modal::begin([
        // 'header' => '<h4>Find device</h4>',
        'options' => [
            'id' => 'sell-modal2',
            'tabindex' => true,

        ],



    ]);

    echo '<div id="modalContent"></div>';

    Modal::end();
    Modal::begin([
        // 'header' => '<h4>Find device</h4>',
        'options' => [
            'id' => 'chek',
            'tabindex' => true,

        ],



    ]);

    echo '<div id="chekContent"></div>';

    Modal::end();

    Modal::begin([
        // 'header' => '<h4>Find device</h4>',
        'options' => [
            'id' => 'client-modal',
            'tabindex' => true,
        ],

       // 'size' => 'modal-sm',

    ]);

    echo '<div id="clientContent1"></div>';

    Modal::end();
    Modal::begin([
        'header' => '<h2>Yeni müştəri  yarat</h2>',
        'id' => 'client-create',
        'size' => 'modal-sm',

    ]);

    echo '<div id="clientContent1"></div>';

    Modal::end();
    Modal::begin([
        // 'header' => '<h4>Find device</h4>',
        'options' => [
            'id' => 'dclient-modal',
            'tabindex' => true,
        ],

        'size' => 'modal-lg',

    ]);

    echo '<div id="dclientContent"></div>';

    Modal::end();
    //--------------------------------
    Modal::begin([
        // 'header' => '<h4>Find device</h4>',
        'options' => [
            'id' => 'return-modal',
            'tabindex' => true,
        ],

        'size' => 'modal-lg',

    ]);

    echo '<div id="returnContent"></div>';

    Modal::end();

    Modal::begin([
        // 'header' => '<h4>Find device</h4>',
        'options' => [
            'id' => 'postponed-modal',
            'tabindex' => true,


        ],
        'size' => 'modal-rena-lg',



    ]);

    echo '<div id="postponedContent"></div>';

    Modal::end();
    ?>
    <div class="row noprint" >
        <?php Pjax::begin(['id' => 'grid-arrival' ]); ?>
    <div class="col-md-9">



    <?php $store=\app\models\Store::find()->all();?>

    <br>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'tableOptions' => [
            'style'=>'width:100%',
            'class' => 'table-rena table-rena2',

        ],


        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
        /*    [

                'label' =>'Info',
                'value' => 'getImage',
                'format' => 'raw',

            ],*/
            [

                'label' =>'Malın adı',

                'value' => 'idMenu.name',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true]
                ],
                'filterType' => GridView::FILTER_SELECT2,
                'width' => '550px',
                'filterInputOptions' => ['placeholder' => 'Any type']
            ],
            [
                'attribute' => 'quantity',
                'label' =>'Say',

                'width' => '80px',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                    return Html::input('text', 'quantity[]', $model->quantity, ['class' => 'form-control input-sm', 'size' => '3', 'onChange' => "editQuantity($model->id,this.value)"]);
                }
            ],

            [
                'attribute' => 'price',
                'label' => 'Qiymeti',
                'width' => '60px',
                'format' => 'raw',
             'value' => function ($model, $index, $widget) {
                    return Html::input('text', 'price[]', $model->price, ['class' => 'form-control', 'style' => 'width:60px !important','size' => '2', 'onChange' => "editPrice($model->id,this.value)"]);
                }
            ],
            'sum',






            ['class' => 'kartik\grid\ActionColumn', 'template' => '{delete}'],
        ],
            // 'debt',

    ]); ?>

</div>
    <div class="col-md-3">
        <div class="thumbnail" style=" background:#FFFFE4;width:280px; height:380px;font-size: 30px;  text-align:center;margin-top:-10px">
            <div style=" border-bottom:solid 1px;padding:7px;">Masa
                <?=Select2::widget([
                    'data' =>  ArrayHelper::map($place, 'id', 'name'),
                    'name' => 'contractor',
                    'value' =>$current,
                    'options' => [
                        'placeholder' => 'Seçin',

                        'id'=>'place',

                    ]
                ]); ?>
			
            </div>
			 <div style=" border-bottom:solid 1px;padding:7px;">Ofisiant
                <?=Select2::widget([
                    'data' =>  ArrayHelper::map($byuer, 'id', 'name'),
                    'name' => 'contractor',
                    'value' =>$current_byuer,
                    'options' => [
                        'placeholder' => 'Seçin',

                        'id'=>'byuer',

                    ]
                ]); ?>

            </div>
        <div style=" border-bottom:solid 1px;">Yekun<br><span id="sum" style="font-size: 50px;color:#BB0000;"><?php echo round($sum, 3) ?></span></div>
        <div>
            Verdi <input type="text" style="width: 80px; height:30px; margin-top:20px; text-align:center;" class="text ui-widget-content ui-corner-all" name="money" id="money" value="0" /> <br>
            Qaytarılmalıdır: <br/><span style="font-size: 50px;" id="sdacha">0</span>
        </div>
        </div>
  <!--      <?=Select2::widget([
            'data' =>  [0=>'Malar aparılır',1 => 'Malar aparılmır'],
            'name' => 'postponed',
            //'hideSearch' =>true,
            'options' => [
                'placeholder' => 'Seçin',

                'id'=>'postponed',

            ]
        ]); ?>-->
        <br>
<br>


        <br>
        <?= Html::button('<i class="glyphicon glyphicon-ok"></i> <i class="glyphicon glyphicon-send"></i> OK', ['class' => 'btn btn-success','id'=>'received', 'onclick' => 'receivedSell($("#money").val(),$("#place").val(),$("#byuer").val())']); ?>
        <?= Html::button('<i class="glyphicon glyphicon-ok"></i> <i class="glyphicon glyphicon-send"></i> Gözləməyə', ['class' => 'btn btn-info','id'=>'postponed1', 'onclick' => 'receivedSell2($("#money").val(),$("#place").val(),$("#byuer").val())']); ?>

        <?= Html::button('<i class="glyphicon glyphicon-remove"></i> Ləğv Et', ['class' => 'btn btn-danger','id' => 'deleteAll', 'onclick' => 'deleteAll()']); ?>

    </div>

        <?php
        $script = <<< JS


 $(document).ready(function(){

 $('body').keydown(function(event){
 //alert(event.which);

 //if ( event.which==113 )  { $("#sell_dialog").click(); event.which=0;}
	//if ( event.which==18 )  { $("#postponed1").click(); event.which=0;}
    });


   $('#money').keyup(function(){

    var sdacha=$("#money").val()-$("#sum").text()  ;
    sdacha=sdacha.toFixed(2)
    $("#sdacha").html(sdacha);

});
});
JS;
        $this->registerJs($script);
        ?>
        <?php Pjax::end(); ?>
    </div>
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

<style>
    @media print {
        .noprint, .modal-header {
            content: " ";
            display: none !important;;visibility: hidden !important;
        }
        .modal-content
        {
            border: none !important;
        }
    }
</style>