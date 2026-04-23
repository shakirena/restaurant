<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
use \app\models\Product;
use yii\helpers\ArrayHelper;
use app\models\Byuer;
use app\models\Place;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArrivalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Arrivals';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div id="inf" style="width:350px;">
Tarix: <?=$date?> <br><br>
<table style="width:90%" >
 <tr   >
    <th>Adı</th>
    <th>Miqdar</th>
    <th>Cəmi</th>
  </tr>
 <?php
 $sum=0;
	foreach ($sell as $model)
	{
		$sum=$sum+$model->sum;
		echo "
				<tr >
						<td>$model->menuName</td>
						<td>$model->quantity</td>
						<td>$model->sum</td>
				</tr>
		
		";
	
	
	}
	echo "
 <tr>
	<td colspan=2>Cəmi</td>
	<td>$sum</td>
 </tr>";
 
 ?>
 </table>      
</div>


<?php
$script = <<< JS
 window.print();


			   

JS;
$this->registerJs($script);
?>
<?php
$this->registerCss("table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 5px;
  text-align: left;    
}");

?>