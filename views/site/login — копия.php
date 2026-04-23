<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Store;
$this->title = 'Login::: TuReN';
?>
<div class="container  ">
    <div class="col-md-4"> </div>
    <div class="col-md-4 site-login">
        <!-- <h1><? /*= Html::encode('Login') */ ?></h1>-->
			<!--<?= Html::img("img/turen.ico",['width'=>'50px','height'=>'40px','style'=>'margin: 10px 50%'])?>-->
        


        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-7\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-4 control-label'],
            ],
        ]); ?>

        <?= $form->field($model, 'username') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-2 col-lg-7\">{input} {label}</div>\n<div class=\"col-lg-7\">{error}</div>",
        ]) ?>

        <div class="form-group">
            <div class="col-lg-offset-8 col-lg-2">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-md-4"></div>
</div>
<br/><br/><br/><br/><br/><br/>
<html>
											  <body bgcolor="#808080">
											  
											  <style>
   h1 {
    font-family: Verdana, Arial, Helvetica, sans-serif; 
   } 
   p {
    font-family: 'Times New Roman', Times, serif; 
    font-style: normal; 
   }
   p {
    font-size: 14px;
   }
   
  </style>
											  <table style="width:100%; height:100%; background-color:#E4E4CC" border="0">
											  <tr>
											  
											  							  
											  <td>
											  
											  <p align="center" > 
											 
											 <i> Texniki dəstək:&nbsp &nbsp  Tural M. Süleymanov </i> <br/> 
											  <b>"TuReN" </b>Computer Service and Group of Programmers<br/> 
											  <b>Mob:</b> +994 70/55 589 50 86  &nbsp &nbsp <b>Whatsapp: </b>+994 55 589 50 86<br/>
											  
											 <b> E-mail: </b>turensoft@gmail.com  &nbsp &nbsp <b> Web:</b> <a href="#">www.turensoft.com </a><br/>
											  
											  </font>
											  </p>
											  </td>
											  </tr>										 
											    <tr>
											  
											  <td>
											  <p align="center"> ______________________________________________________________________<br/>
											   Copyright 2015-2019 © All rights reserved  </p>
											  </td>
											  
											  </tr> 
                                             </table>
                                                 </body>
												 </html>

