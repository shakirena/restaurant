<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\models\Roles;
use app\models\Store;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">


    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i>Əlavə et', ['value' => Url::to(['create']), 'class' => 'btn btn-danger', 'id' => 'users']) ?>
    </p>
    <?php $roleList = ArrayHelper::map(Roles::find()->all(), 'id', 'name'); ?>

    <?php
    Modal::begin([
        'header' => '<h2>Yeni istifadəçi adının açılması</h2>',

        'id' => 'users-create',
        'size' => 'modal-sm',

    ]);

    echo '<div id="modalContent"></div>';

    Modal::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions'=>[
            'style'=>'width:800px',
            'class' => 'table-rena table-rena2 ',

        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

           // 'id_user',
            'fio',


            'login',
            //'password',

            [
                'attribute' => 'id_role',
                'filter' => $roleList,
                'value' => 'idRole.name',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true]
                ],
                'filterType' => GridView::FILTER_SELECT2,
                'width' => '200px',
                'filterInputOptions' => ['placeholder' => 'Any type']
            ],

			[
				'attribute' => 'disabled',
				'value' => 'disabledVal',
				'format' => 'raw',
			],
            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>
</div>
