<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use \app\models\TypeProduct;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DeviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


?>
<div class="device-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'style' => 'width:800px;cursor:pointer',
            'class' => 'table-rena table-rena2',
        ],
        'pjax' =>true,
        'hover'=>true,
        'striped' =>true,
        'rowOptions' =>
            function ($dataProvider, $key, $index, $grid) {
                return ['id' => $dataProvider['id'],
                   //'value' => Url::to(['arrival/add']),
                    'onClick'=>"addArrival(this.id,'$dataProvider[name]')"
                ];
            },

        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'name',
             
          //  'bar_code',


        ],
    ]); ?>

</div>
<?php
$script = <<< JS
function addArrival(id,name)
{
	var quantity=prompt("Neçə ədəd   "+name+" ?",1);
    if (quantity) {
				$.get('insert', {quantity:quantity, id:id},function(){
			   
				 });
         }
	
}

function addArrivalReceived(quantity,usd,price,id,pricesell,proc,pack,pricetop)
{
   $.get('insert', {quantity:quantity,usd:usd,price:price,id:id,pricesell:pricesell,proc:proc,pack:pack,pricetop:pricetop},function(){
   $("#current").modal("hide");
   //$.pjax.reload({container:"#grid-arrival"});
    });

  //  alert(quantity);
}
JS;
$this->registerJs($script);
?>