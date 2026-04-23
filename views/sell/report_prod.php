<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use app\models\Type;
use app\models\Users;
use app\models\Contractor;
use app\models\Store;
use app\models\Byuer;
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
	<?php $menuList = ArrayHelper::map(Product::find()->all(), 'id', 'name'); ?>
    <?php $typeList = ArrayHelper::map(Type::find()->all(), 'id', 'name'); ?>
	<?= Html::button('<i class="glyphicon glyphicon-ok"></i> <i class="glyphicon glyphicon-send"></i> Print', ['class' => 'btn btn-success','id'=>'received', 'onclick' => 'printDay()']); ?>
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

        'datetime',
    [
                'attribute' => 'date',
                'label' => 'Satış tarixi',
                'format'=>'raw',
                'value' => 'date',
                'width' => '150px',

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

                'width' => '350px',
              
                
            ],
            [

                 'attribute' =>'id_type',
                 'label' => 'Yəmək gruppu',
                 'value' => 'idMenu.idType.name',
                 'width' => '350px',
                 'filter' => $typeList,
                 'filterWidgetOptions' =>[
                     'pluginOptions'=>['allowClear'=>true,'multiple' =>true]
                 ],
                 'filterType' =>GridView::FILTER_SELECT2,
 
                 'filterInputOptions' =>['placeholder'=>'Any ']
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
                'value' =>function ($model, $index, $widget) {
                    return round($model->sum,2);
                },
				'label'=>'Cəmi',
                'width' =>'100px',
                'hAlign' => 'right',
                 'footer' =>  round($searchModel->getSumArrival($dataProvider->query,'sum'),2)
            ],

        ],
    ]); ?>

  </div>
