<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Arrival;
use app\models\TypeProduct;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

use unclead\multipleinput\MultipleInput;
$model1=Arrival::find()->where(["id_product"=>$model->id_product])->orderBy("id DESC")->one();
$model2=Arrival::find()->select("sum(rest) as rest")->where(["id_product"=>$model->id_product, 'received' => 1])->groupBy("id_product")->one()->rest;
/* @var $this yii\web\View */
/* @var $model app\models\Arrival */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="arrival-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($product, 'name')->textInput(["value" => $product->name]) ?>
   
    <?= $form->field($model, 'price')->textInput(["value" => $model1->price]) ?>

    <?= $form->field($model, 'rest')->textInput(["value" => $model2]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Yaddaşa ver', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
