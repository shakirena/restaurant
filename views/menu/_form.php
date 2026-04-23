<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Type;
use app\models\Product;
use app\models\Store;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>





    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'id_type')->widget(Select2::className(),[
        'data' =>  ArrayHelper::map(Type::find()->all(), 'id', 'name'),
        'options' => [
            'placeholder' => 'Seçin',

        ]

    ]); ?>

    <?= $form->field($model, 'price')->textInput() ?>
	<?= $form->field($model, 'price_vip')->textInput() ?>
    <?= $form->field($model, 'id_store')->widget(Select2::className(),[
        'data' =>  ArrayHelper::map(Store::find()->all(), 'id', 'name'),
        'options' => [
            'placeholder' => 'Seçin',

        ]

    ]); ?>
      <?= Select2::widget([
        'data' =>  ArrayHelper::map(Product::find()->all(), 'id', 'name'),
        'name' => 'product',
        'options' => [
            'placeholder' => 'Seçin',
            'onchange'=>"insertProduct($model->id)",
            'id'=>'product',

        ]
    ]); ?>
<br>
    <?php Pjax::begin([
        'enablePushState' => false,
        'id' => 'product_view',


    ]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'captionOptions'=>['encode' => false],
        'showPageSummary' => true,
        'tableOptions' => [

            'class' => 'table-rena',

        ],

        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],



            [
                'attribute' =>'id_product',
                'label' =>'Malın adı',
                'value'=>'idProduct.name',
                'width' => '350px',


            ],
            [
                'attribute' => 'quantity',
                'label' =>'Miqdar',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {

                    return Html::input('text', 'quantity[]', $model->quantity, ['class' => 'form-control input-sm', 'size' => '3', 'onChange' => "editQuantityMenu($model->id,this.value)", 'id'=> 'quantity'.$model->id]);
                }
            ],

            [
                'value' =>  'price',
                'label' =>'Son alış <br> qiyməti',
                'format'=>'raw',
                'encodeLabel' => false,
                'width' =>'100px',
                'hAlign' => 'right',

            ],
			
            [
                'value' => 'sum',
                'label' =>'Cəmi',
                'width' =>'100px',
                'encodeLabel' => false,
                'pageSummary' => true,
                'hAlign' => 'right',
               // 'footer' => round($searchModel->getSumArrival($dataProvider->query,'sum'),2)
            ],


            ['class' => 'kartik\grid\ActionColumn', 'template' => '{delete}',
                'buttons'  => [
                    'delete'   => function ($url, $model1) use ($id)  {

                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript:void(0)',['onclick'=>" deleteConsist($model1->id)"]
                        );
                    },

                ],
             

            ],

        ],
    ]); ?>
    <?php Pjax::end(); ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end();$id=$model->id ;?>
</div>
<?php
$script = <<< JS




function deleteProduct(id_product,id_menu){
 if (!id) id=-1;
   $.get('delete-product', {id_product:id_product,id_menu:id_menu},function(data){
        $.pjax.reload({container:'#product_view',data:{id:id}, type: "GET", push:false,replace:false, url:'update'});
    });
};
JS;
$this->registerJs($script);
?>