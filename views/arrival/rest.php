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
use app\models\TypeProduct;
use app\models\Store;
use kartik\select2\Select2;
$this->title = 'Arrivals';

?>
<div class="arrival-index">
    <?php $contractorList = ArrayHelper::map(Contractor::find()->all(), 'id', 'name'); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'striped'=>true,
        'hover'=>true,
        'showFooter' => true,
        'tableOptions' => [

            'class' => 'table-rena table-rena2',
            'style' => 'font-size:9pt'

        ],
        'footerRowOptions' => ['style' => 'font-weight:bold;text-decoration:underline;color:red;'],
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
                'value' => 'idProduct.name',
                'format'=>'raw',
                'subGroupOf'=>1,
                'group'=>true,
                'width' => '300px',
            ],
            [
                'attribute' =>'rest',
                'label'=>'Anbarda <br> sayı',
                
				'value' => function ($model, $index, $widget) {
                    return round($model->rest,2);
					},
                'format'=>'raw',
                'width' =>'30px',
                'encodeLabel' => false,
                'footer' => $searchModel->getSumRest($dataProvider->query,'rest')
              
            ],

            [
                'attribute' =>  'price ',
                'format'=>'raw',
                'value' => 'priceRest',
                'width' =>'30px',
                'label' => 'Gəliş qiym.<br> (azn)',
                'encodeLabel' => false,
              

            ],	
            [
               
                  'format'=>'raw',
                  'value' => 'priceSum',
                  'width' =>'30px',
                  'label' => 'Gəliş qiym. <br>cəmi (azn)',
                   'encodeLabel' => false,
                  'footer' => round($searchModel->getSumRest($dataProvider->query,"sum"),2)
  
            ],
		    [   'attribute' => 'id_contr',
                'value' => 'idContr.name',
                'filter' => $contractorList,
                'filterWidgetOptions' =>[
                    'pluginOptions'=>['allowClear'=>true]
                ],
                'filterType' =>GridView::FILTER_SELECT2,
                'width' => '200px',
                'filterInputOptions' =>['placeholder'=>'Any ']
            ],


        ],
    ]); ?>
  </div>
  
  
