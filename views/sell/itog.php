<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use app\models\Client;
use app\models\Contractor;
use app\models\Product;
use kartik\select2\Select2;

use app\models\TypeProduct;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SellSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sell-index row" xmlns="http://www.w3.org/1999/html">
<br>
 <div class="btn-group">
		<?=Select2::widget([
        'data' =>  ArrayHelper::map(Product::find()->all(), 'id', 'name'),
        'name' => 'product',
        'options' => [
            'placeholder' => 'Seçin',

            'id'=>'product',

        ]
    ]); ?>
 </div>
  <div class="btn-group">
        <?= DatePicker::widget([
            'name' => 'check_issue_date',
            'id' => 'date1',
            'value' => date('Y-m-d'),
            'options' => ['placeholder' => 'Select issue date ...'],
            'type' => DatePicker::TYPE_INPUT,
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => false
            ]
        ]); ?>
    </div>
    <div class="btn-group">
        <?= DatePicker::widget([
            'name' => 'check_issue_date',
            'id' => 'date2',
            'value' => date('Y-m-d'),
            'options' => ['placeholder' => 'Select issue date ...'],
            'type' => DatePicker::TYPE_INPUT,
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => false
            ]
        ]); ?>
    </div>
	  <div class="btn-group">
            <?= Html::button('<i class="glyphicon glyphicon-ok"></i>  OK', ['class' => 'btn btn-success','onclick' =>"document.location.href='itog-received?&date1='+$('#date1').val()+'&date2='+$('#date2').val()+'&product='+$('#product').val()"]); //?>

        </div>
		</br></br>
		
<b><?=$date1." - ".$date2?></br>	</b></br>
<?php
$dolg=-$dolg;
?>	
<div>Продаж: <?=round($sell,2)?> AZN</div>
---------------------------------------------------------<br/>
<div>Приход <?=round($arrival,2)?>AZN</div>
---------------------------------------------------------<br/>


</div>




