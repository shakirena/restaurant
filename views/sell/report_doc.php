<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use app\models\TypeProduct;
use app\models\Users;
use app\models\Contractor;
use app\models\Store;
use app\models\Byuer;
use kartik\select2\Select2;
use app\models\Returnp;
use app\models\ReturnpSearch;
use app\models\SellSearch;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ArrivalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Arrivals';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="arrival-index">




    <?php
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
    <?php
    Modal::begin([
        // 'header' => '<h4>Find device</h4>',
        'options' => [
            'id' => 'kartik-modal',
            'tabindex' => '-1',
        ],

        'size' => 'modal-lg',

    ]);

    echo '<div id="modalContent"></div>';

    Modal::end();
    ?>
    <br> <br>
    <?php $user = ArrayHelper::map(Byuer::find()->all(), 'id', 'name'); ?>
    <?php $storeList = ArrayHelper::map(Store::find()->all(), 'id', 'name'); $i=0;$num=0;?>
    <?php $contractorList = ArrayHelper::map(Contractor::find()->andWhere("id>1")->all(), 'id', 'name'); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'tableOptions' => [

            'class' => 'table-rena table-rena2',
            'style' => 'font-size:9pt'

        ],

        //  'pjax'=>true,
        'striped'=>true,
        'hover'=>true,
        'panel'=>['type'=>'primary'],
        'showPageSummary' => true,
        'rowOptions' =>
            function ($dataProvider, $key, $index, $grid) {
                return ['id' => $dataProvider['id']
                ];
            },
        'showFooter' => true,
        'footerRowOptions' => ['style' => 'font-weight:bold;text-decoration:underline;color:red;'],

        'columns' => [

         //   ['class'=>'kartik\grid\SerialColumn'],

			[
				'class' => 'kartik\grid\ExpandRowColumn',
				'width' => '50px',
				'value' => function ($model, $key, $index, $column) {
					return GridView::ROW_COLLAPSED;
				},
				// uncomment below and comment detail if you need to render via ajax
				// 'detailUrl' => Url::to(['/site/book-details']),
				'detail' => function ($model, $key, $index, $column) {
				 $searchModel= new ReturnpSearch(['id_sell' => "$model->number"]);
				 $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
				 
				 $searchModel1 = new SellSearch(['number' => $model->number]);
				 $dataProvider1 = $searchModel1->search(Yii::$app->request->queryParams);
					return Yii::$app->controller->renderPartial('_return-details', ['searchModel' =>$searchModel, 'dataProvider' =>$dataProvider,  'dataProvider1' =>$dataProvider1, 'searchModel1' =>$searchModel1]);
				},
				//'headerOptions' => ['class' => 'kartik-sheet-style'] ,
				'expandOneOnly' => true
			
			],
            [
                'attribute' => 'number',
                'format'=>'raw',
                'value' => 'getNumberSell',
                

            ],
            [
                'attribute' => 'id_place',
                'label' => 'Masa',
                'value' => 'idPlace.name',
                //'group'=>true,
            ],
            'datetime',
            [
                'attribute' => 'date',
                'label' => 'Satış tarixi',
                'format'=>'raw',
                'value' => 'date',
                'width' => '150px',

                'filter' =>DatePicker::widget([
                    //,

                    'model' => $searchModel,
                    'attribute' => 'date_start',
                    'value' => date('Y-m-d'),
                    //'options' => ['placeholder' => 'Select issue date ...'],
                    'type' => DatePicker::TYPE_RANGE,
                    'attribute2' => 'date_end',
                    'value2' => date('Y-m-d'),
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoClose' => true
                        // 'todayHighlight' => false
                    ]
                ]),

                // 'group'=>true,

            ],
           


            [
                'attribute' => 'quantity',
                'label' =>'Miqdar',
                'format'=>'raw',
                'value' => 'quantity',
                'width' =>'100px',
                'hAlign' => 'right',
                'footer' =>  round($searchModel->getSumArrival($dataProvider->query,'quantity'),2)

            ],

          
            [
                'attribute' => 'sum',
                'value' =>'sum',
                'width' =>'100px',
                'hAlign' => 'right',
                'footer' =>  round($searchModel->getSumArrival($dataProvider->query,'sum'),2)
            ],

            [   'attribute' => 'client',
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

			[
				'label' => 'Ləğv edilənlər',
				'value' => 'returnCount',
				'encodeLabel' =>false,
				'format' => 'raw'
			]

     

        ],
    ]); ?>

  </div>
