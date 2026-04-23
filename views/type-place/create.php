<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TypePlace */

$this->title = 'Create Type Place';
$this->params['breadcrumbs'][] = ['label' => 'Type Places', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-place-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
