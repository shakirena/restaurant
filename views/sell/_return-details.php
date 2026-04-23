<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\ClientFio;
use app\models\Users;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ReturnpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


?>
<div class="returnp-index">
    <?php $user = ArrayHelper::map(Users::find()->all(), 'id', 'fio'); ?>
	
	 <?= GridView::widget([
        'dataProvider' => $dataProvider1,
        'tableOptions' => [

            'class' => 'table-rena table-rena2',
            'style' => 'font-size:9pt;width:600px'
        ],

        //  'pjax'=>true,
        'striped'=>true,
        'hover'=>true,
        'showPageSummary' => true,
        'rowOptions' =>
            function ($dataProvider, $key, $index, $grid) {
                return ['id' => $dataProvider['id']
                ];
            },
        'showFooter' => true,
        'footerRowOptions' => ['style' => 'font-weight:bold;text-decoration:underline;color:red;'],

        'columns' => [
          
            [
				
                'attribute' => 'id_place',
                'label' => 'Masa',
				'value' => 'idPlace.name',	
                'group'=>true,
				'subGroupOf' =>0,
				
				
            ],

          
            [
                'attribute' =>'id_menu',
                'value' => 'idMenu.name',
                'width' => '550px',
            ],
            [
                'attribute' => 'quantity',
                'label' =>'Miqdar',
                'format'=>'raw',
				'filter'=>false,
                'value' => 'quantity',
                'width' =>'70px',
                'hAlign' => 'right',
                //'footer' =>  round($searchModel1->getSumArrival($dataProvider->query,'quantity'),2)

            ],

            [
                'attribute' =>  'price',
                'label' => 'Satış <br> qiyməti',
                'format'=>'raw',
                'value' => 'price',
				'filter'=>false,
                'width' =>'70px',
                'encodeLabel' => false,
                'hAlign' => 'right',
            ],
			[
                  
                    'value' => 'sumValue',
                    'enableSorting' => false,
                    'pageSummary' => true,
                    'hAlign' => 'right',
                    'width' => '10px'
            ],
			[
					'attribute' =>   'discount',
			],
            [
                'attribute' => 'sum',
				'label'=>'Cəmi',
				'filter'=>false,
                'value' =>'sum',
                'width' =>'100px',
                'hAlign' => 'right',
                //'footer' =>  round($searchModel1->getSumArrival($dataProvider->query,'sum'),2)
            ],

            [   'attribute' => 'id_client',
                'label' => 'Müştəri',
                'value' => 'idClient.fio',
                'format' => 'raw',
                'width' => '300px',
                'filter' => $clientList,
                'filterWidgetOptions' =>[
                    'pluginOptions'=>['allowClear'=>true]
                ],
                'filterType' =>GridView::FILTER_SELECT2,

                'filterInputOptions' =>['placeholder'=>'Any ']

            ],
            [   'attribute' => 'id_user',
                'label' => 'Satıcı',
                'value' => 'idUser.fio',
                'filter' => $user,
                'filterWidgetOptions' =>[
                    'pluginOptions'=>['allowClear'=>true]
                ],
                'filterType' =>GridView::FILTER_SELECT2,
                'width' => '200px',
                'filterInputOptions' =>['placeholder'=>'Any type']
            ],
          
        ],
    ]);
	?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
      
        'striped'=>true,
        'hover'=>true,
        'showFooter' => true,
        'tableOptions' => [

            'class' => 'table-rena table-rena2',
            'style' => 'font-size:9pt;width: 600px'

        ],
        'footerRowOptions' => ['style' => 'font-weight:bold;text-decoration:underline;color:red;'],

        // 'pjax'=>true,
        
        'columns' => [
           
            [
                'attribute' =>'id_product',
                'label' =>'Malın adı',
                'value' => 'idProduct.name',
                'width' => '200px',
            ],
           [
               'attribute' => 'quantity',
               'footer' => $searchModel->getSumReturn($dataProvider->query),
           ] ,
            [
                'attribute'=> 'reason',
                'width' => '200px',
            ],
         
            [   'attribute' => 'id_user',
                'value' => 'idUser.fio', 
                'width' => '250px',
                'filterInputOptions' =>['placeholder'=>'Any type']
            ],




        ],
    ]); ?>

</div>
