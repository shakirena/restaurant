<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\models\Type;
use app\models\Product;
use app\models\Store;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel app\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


?>
<div class="menu-index">

    <?php
    Modal::begin([
        'header' => '<h2>Yeni mal    açılması</h2>',

        'size' => 'modal-sm',
        'options' => [
            'id' => 'menu-create',
            'tabindex' => true,
        ],
    ]);

    echo '<div id="modalContent"></div>';

    Modal::end();
    ?>
    <br>
  <?php $typeList = ArrayHelper::map(Type::find()->all(), 'id', 'name'); ?>
  <?php $productList = ArrayHelper::map(Product::find()->all(), 'id', 'name'); ?>
  <?php $storeList = ArrayHelper::map(Store::find()->all(), 'id', 'name'); ?>
    <p>

                       
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i>Əlavə et', Url::to(['create']),[ 'class' => 'btn btn-danger']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions'=>[
            'style'=>'width:900px',
            'class' => 'table-rena table-rena2 ',
        ],
        'pjax' =>true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //  'id',
            'name',
            [
                'attribute' => 'id_type',
                'filter' => $typeList,
                'value' => 'idType.name',
                'filterWidgetOptions' =>[
                    'pluginOptions'=>['allowClear'=>true]
                ],
                'filterType' =>GridView::FILTER_SELECT2,
                'width' => '200px',
                'filterInputOptions' =>['placeholder'=>'Any ']


            ],
         
            // 'note',
            'price',
			'price_vip',
			
			[
               'attribute' => 'id_store',
			   'label' => 'Bölmə',
                'filter' => $storeList,
                'value' => 'idStore.name',
                'filterWidgetOptions' =>[
                    'pluginOptions'=>['allowClear'=>true]
                ],
                'filterType' =>GridView::FILTER_SELECT2,
                'width' => '200px',
                'filterInputOptions' =>['placeholder'=>'Any ']


            ],
			[
				'attribute' => 'disabled',
				'value' => 'disabledVal',
				'format' => 'raw',
			],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
