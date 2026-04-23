<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\models\TypePlace;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PlaceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


?>
<div class="place-index">
  <?php $typeList = ArrayHelper::map(TypePlace::find()->all(), 'id', 'name'); ?>
    <?php
    Modal::begin([
        'header' => '<h2>Yeni massa    açılması</h2>',

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

    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i>Əlavə et', ['value' => Url::to(['create']), 'class' => 'btn btn-danger', 'id' => 'menu']) ?>
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
            'name',
			[
               'attribute' => 'id_type',
			   'label' => 'Masa yerləşməsi',
                'filter' => $tpeList,
                'value' => 'idType.name',
                'filterWidgetOptions' =>[
                    'pluginOptions'=>['allowClear'=>true]
                ],
                'filterType' =>GridView::FILTER_SELECT2,
                'width' => '200px',
                'filterInputOptions' =>['placeholder'=>'Any ']


            ],
            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>
</div>
