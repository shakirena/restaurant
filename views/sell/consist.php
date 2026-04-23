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
use app\models\Menu;
use kartik\select2\Select2;
use app\models\Product;
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
	<?php $menuList = ArrayHelper::map(Menu::find()->all(), 'id', 'name'); ?>
    <?php $productList = ArrayHelper::map(Product::find()->all(), 'id', 'name'); ?>
	     <br> <br>

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
                'attribute' => 'datetime',
                'label' => 'Satış tarixi',
                'format'=>'raw',
                'value' => 'datetime',
                'width' => '150px',
                'group'=>true,
                'filter' =>DatePicker::widget([
                   'model' => $searchModel,
                    'attribute' => 'date_start',
                    'value' => date('Y-m-d'),
                    'type' => DatePicker::TYPE_RANGE,
                    'attribute2' => 'date_end',
                    'value2' => date('Y-m-d'),
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoClose' => true
                       
                    ]
                ]),


            ],
           
            [
                'attribute' =>'id_menu',
                'value' => 'idMenu.name',
                'group'=>true,
                'filter' => $menuList,
                'filterWidgetOptions' =>[
                    'pluginOptions'=>['allowClear'=>true]
                ],
               // 'subGroupOf'=>1,
                'filterType' =>GridView::FILTER_SELECT2,
                'width' => '200px',
                'filterInputOptions' =>['placeholder'=>'Any '],
                'groupHeader' =>function ($model, $index, $widget) {
                    return [
                        'mergeColumns' => [[1,2]],
                        'content' =>[
                            1=> ' Summary',
                            3=>GridView::F_SUM,
                            5=>GridView::F_SUM,
                            6=>GridView::F_SUM,



                        ],
                        'contentFormats' => [
                            3=> ['format'=>'number','decimals'=>2],

                            5=> ['format'=>'number','decimals'=>2],

                            6=> ['format'=>'number','decimals'=>2],
                        ],
                        'contentOptions' => [
                            1 => ['style' => 'font-variant:small-caps'],
                            4 => ['style' => 'text-align:right'],
                            5 => ['style' => 'text-align:right'],
                            6 => ['style' => 'text-align:right'],
                          
                        ],
                        'options' => ['class' => 'danger','style'=> 'font-weight:bold']
                    ];
                },
            ],
            [
                'value' =>'getQuantity',
                'width' => '350px',
                'group'=>true,
                'subGroupOf'=>0,
            ],
            [
                'attribute' =>'sum',
                'filter' => false,
                'width' => '350px',
                'group'=>true,
                'subGroupOf'=>0,
            ],
            [

                'attribute' =>'id_product',
                'value' => 'productName',
                'width' => '350px',
                'filter' => $productList,
                'filterWidgetOptions' =>[
                    'pluginOptions'=>['allowClear'=>true]
                ],
                'filterType' =>GridView::FILTER_SELECT2,
                'width' => '200px',
                'filterInputOptions' =>['placeholder'=>'Any '],
            ],
            [
                
                'label' =>'Miqdar',
                'format'=>'raw',
                'value' => 'getQuantityConsist',
                'width' =>'100px',
                'hAlign' => 'right',
                'footer' =>  round($searchModel->getSumConsist($dataProvider->query,'quantity'),2)

            ],
            [
                'format'=>'raw',
                'label' => 'Məbləğ (azn)',
                'value' => 'priceConsist',
                'width' =>'100px',
                'hAlign' => 'right',
                'footer' =>  round($searchModel->getSumConsist($dataProvider->query,'price'),2)

            ],

         

        ],
    ]); ?>

  </div>
