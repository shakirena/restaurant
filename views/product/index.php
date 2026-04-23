<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


?>
<div  class="menu-index">

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
            
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
