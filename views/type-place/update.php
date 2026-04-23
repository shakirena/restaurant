<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TypePlace */

$this->title = 'Update Type Place: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Type Places', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="type-place-update update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
