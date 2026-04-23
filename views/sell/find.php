<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use \app\models\Type;
/* @var $this yii\web\View */
/* @var $searchModel app\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Menus';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <?php $typeList = ArrayHelper::map(Type::find()->all(), 'id', 'name'); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions'=>[
            'style'=>'width:800px',
            'class' => 'table-rena table-rena2 ',
        ],
        'pjax' =>true,
        'rowOptions' =>
            function ($dataProvider, $key, $index, $grid) use ($id_client){

                return ['id' => $dataProvider['id'],
                    //'onClick'=>'addSell2(this.id)'
                ];
            },
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

          //  'id',
           'name',
		   'price',
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
            



        ],
    ]); ?>
</div>
