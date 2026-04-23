<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Place;
?>
<div class="container  ">
   
    <div class="col-md-2">
    


		<div class='form-horizontal'>
		<?=
			   Select2::widget([
				'data' => ArrayHelper::map(Place::find()->orderBy("id DESC")->all(), 'id', 'name'),
				'name' => 'place',
				'options' => [
					'placeholder' => 'Seçin',
					'id'=>'place',

				],
				
			]); ?>
</br>
    
        <div class="form-group">
            <div class="col-lg-offset-8 col-lg-2">
                <?= Html::button('OK', ['class' => 'btn btn-primary', 'name' => 'login-button','onclick'=>"receivedEditPlace($id)"]) ?>
            </div>
        </div>

        </div>
    </div>
    <div class="col-md-4"></div>
</div>

