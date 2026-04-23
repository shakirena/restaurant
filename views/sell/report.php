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
use app\models\Product;
use app\models\Users;
use app\models\Contractor;
use app\models\Store;
use app\models\Client;
use app\models\Returnp;
use app\models\ReturnpSearch;
use app\models\Byuer;
use kartik\select2\Select2;

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

	Modal::begin([
       // 'header' => '<h4>Find device</h4>',
        'options' => [
            'id' => 'login-modal',
            'tabindex' => '-1',
        ],

        'size' => 'modal-sm',

    ]);

    echo '<div id="loginContent"></div>';
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
    <?php $user = ArrayHelper::map(Users::find()->all(), 'id', 'fio'); ?>
	<?php $byuer = ArrayHelper::map(Byuer::find()->all(), 'id', 'name'); ?>
    <?php $storeList = ArrayHelper::map(Store::find()->all(), 'id', 'name'); $i=0;$num=0;?>
	
	 <?php $menuList = ArrayHelper::map(Product::find()->all(), 'id', 'name'); ?>
    <?php $clientList = ArrayHelper::map(Client::find()->all(), 'id_client', 'fio'); $i=0;$num=0;?>
    <?php $contractorList = ArrayHelper::map(Contractor::find()->andWhere("id>1")->all(), 'id', 'name'); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'tableOptions' => [

            'class' => 'table-rena table-rena2',
            'style' => 'font-size:9pt;'
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
				
				
				
                'attribute' => 'number',
                'format'=>'raw',
                'value' => 'getNumberSell',
                'group'=>true,
                'groupHeader' =>function ($model, $index, $widget) {
                    return [
                        'mergeColumns' => [[1,3]],
                        'content' =>[
                            1=> ' Summary (sell note №'.$model->number.')',
                            4=>GridView::F_SUM,
                            6=>GridView::F_SUM,
                            8=>GridView::F_SUM,
                        ],
                        'contentFormats' => [

                            4=> ['format'=>'number','decimals'=>0],
                            6=> ['format'=>'number','decimals'=>2],
							8=> ['format'=>'number','decimals'=>2],
                            9=> ['format'=>'number','decimals'=>2],
                            11=> ['format'=>'number','decimals'=>2],
                            11=> ['format'=>'number','decimals'=>2],
                            13=> ['format'=>'number','decimals'=>2]
                        ],
                        'contentOptions' => [
                            1 => ['style' => 'font-variant:small-caps'],
                            6 => ['style' => 'text-align:right'],
                            7 => ['style' => 'text-align:right'],
                            9 => ['style' => 'text-align:right'],
                            8 => ['style' => 'text-align:right'],

                            11 => ['style' => 'text-align:right'],

                        ],
                        'options' => ['class' => 'danger','style'=> 'font-weight:bold']
                    ];
                },

            ],
			
            [
				
                'attribute' => 'id_place',
                'label' => 'Masa',
				'value' => 'idPlace.name',
				
                'group'=>true,
				'subGroupOf' =>0,
				
				
            ],
                'datetime',
            [
                'attribute' => 'date',
                'label' => 'Satış tarixi',
                'format'=>'raw',
                'value' => 'date',
                'width' => '100px',

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
                'footer' =>  round($searchModel->getSumArrival($dataProvider->query,'quantity'),2)

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
                'footer' =>  round($searchModel->getSumArrival($dataProvider->query,'sum'),2)
            ],

          /*  [
                'label'=>'Mənfəət %',
                'value' => 'procMənfəət'
            ],*/
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
            /*
			   [
                'attribute' =>'name_product',
                'value' => 'idMenu.idProduct.name',
                'pageSummary' => 'Yekun',
                'pageSummaryOptions' => ['class' => 'text-right text-danger'],
               'filter' => $menuList,
                'filterWidgetOptions' =>[
                    'pluginOptions'=>['allowClear'=>true]
                ],
                'filterType' =>GridView::FILTER_SELECT2,
                'width' => '400px',
                'filterInputOptions' =>['placeholder'=>'Any ']

            ],
			 */
           /* [   'attribute' => 'type',
                'value' => 'idType'
            ],*/


            // 'received',

	/*[
				'class' => 'kartik\grid\ExpandRowColumn',
				'width' => '50px',
				8
				'value' => function ($model, $key, $index, $column) {
					return GridView::ROW_COLLAPSED;
				},
				// uncomment below and comment detail if you need to render via ajax
				// 'detailUrl' => Url::to(['/site/book-details']),
				'detail' => function ($model, $key, $index, $column) {
				 $searchModel= new ReturnpSearch(['id_sell' => "$model->number"]);
				 $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
					return Yii::$app->controller->renderPartial('_return-details', ['searchModel' =>$searchModel, 'dataProvider' =>$dataProvider ]);
				},
				//'headerOptions' => ['class' => 'kartik-sheet-style'] ,
				'expandOneOnly' => true
			],*/
        ],
    ]); ?>

  </div>
