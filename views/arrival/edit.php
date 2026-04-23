<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\dateRange\DateRangePicker;
use kartik\grid\GridView;
use app\models\Contractor;

use app\models\Store;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ArrivalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Arrivals';
//$this->params['breadcrumbs'][] = $this->title;

?>
<div class="arrival-index">

   
  <!--  <?php Pjax::begin(); ?>-->


    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'filterModel' => $searchModel,
        //'pjax'=>true,
        'striped'=>true,
        'hover'=>true,
        'showFooter' => true,
        'tableOptions' => [

            'class' => 'table-rena table-rena2',
            'style' => 'font-size:9pt'

        ],
        'footerRowOptions' => ['style' => 'font-weight:bold;text-decoration:underline;color:red;'],

        'panel'=>['type'=>'primary'],
        'rowOptions' =>
            function ($dataProvider, $key, $index, $grid) {
                return ['id' => $dataProvider['id']
                ];
            },

        'columns' => [
            ['class'=>'kartik\grid\SerialColumn'],
              [
                'attribute' =>'name_product',
                'label' =>'Malın adı',
                //'filter' => $productList,
                //'value'=>'idType',
                'value' => 'idProduct.name',
                'format'=>'raw',

               
                'group'=>true,
                'width' => '300px',
                //'filterInputOptions' => ['placeholder' => 'Any type']
            ],
           
            [
                'attribute' =>'rest',
                'label'=>'Anbarda <br> sayı',
                'value' => 'rest',
                'format'=>'raw',
                'width' =>'30px',
                'encodeLabel' => false,
               // 'footer' => $searchModel->getSumRest($dataProvider->query,'rest')
               // 'pageSummary' => true,
            ],
          /*  [

                'label' => 'Gözləmədə',
                'value' =>'postponed',
                'width' =>'80px',
               // 'footer' =>$searchModel->getSumPostponed($dataProvider->query)
                // 'filter' => $storeList,
                // 'footer' => $sum_sumsell,
            ],*/
            [
                'attribute' =>  'price ',
                'format'=>'raw',
                'value' => 'priceRest',
                'width' =>'30px',
                'label' => 'Qiyməti<br> (azn)',
                'encodeLabel' => false,
                //'footer' => round($searchModel->getSumRest($dataProvider->query,"sum"),2)

            ],


          


            //'id_user',
            // 'received',
            ['class' => 'kartik\grid\ActionColumn'],

        ],
    ]); ?>
    <!-- <?php Pjax::end(); ?>-->
  </div>
