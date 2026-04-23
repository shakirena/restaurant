<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\dateRange\DateRangePicker;
use kartik\grid\GridView;
use app\models\Contractor;
use app\models\TypeProduct;
use app\models\Sell;
use app\models\Costs;
use app\models\Returnp;
use app\models\Transfer;
use app\models\Client;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ArrivalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Arrivals';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="arrival-index">
    <table class="table-rena kv-grid-table table table-bordered  kv-table-wrap">
        <thead>
        <th>Tarix</th>
        <th>Alıb</th>
        <th>Ödənilib</th>
        <th>İadə</th>
        <th>Yekun qalıq</th>
		<th>Qeyid</th>
	
        </thead>
        <tbody>
        <?php echo "<tr><td colspan='9' class='danger'>Müştəri: $client</td> </tr>"; ?>
        <?php
        $debt=round($current,2);$sum=0;//round($current->sum,2);
		$sum_debt=0;$sum_voz=0;
	
							echo "<tr>
								<td>Carı borc</td>
								<td></td>	
								<td></td>
								<td></td>
								<td>$debt</td>
								<td></td>			
							</tr>";
				foreach($model as $move) {
					if ($move['debt']>0 || $move['sum']>0) {
						
							$c=$debt;
							$debt=round($debt+$move[debt],2);
							$sum=round($sum+$move[sum],2);
							
							if ($move[debt]==0) $move[debt]=$move[sum];
							else $move[debt]=$move[debt]+$move[sum];
							$sum_debt=$sum_debt+$move[debt];
							$move[sum] = round($move[sum] + $move[pos],2);
							if ($move['number'])
								echo "<tr>
										<td><a href='../sell/report1?number=$move[number]'> Məxaric sənədi ($move[number]) $move[datetime] tarixdən</a></td>
										<td>$move[debt]</td>	
										<td>$move[sum]</td>
										<td></td>
										<td>$debt</td>
										<td>$move[note]</td>
									
								</tr>";
							else 
										echo "<tr>
										<td>$move[note]</td>
										<td>$move[debt]</td>	
										<td>$move[sum]</td>
										<td></td>
										<td>$debt</td>
										<td></td>";
						
						
					}
					else 
					{
						if ($move['number']>0) {
								
									$debt=round($debt+$move[debt],2);
									$return=Returnp::find()->where(['id'=>$move[number]])->one();
									$move[sum]=-$move[sum];
									$sum_voz=$sum_voz+$move[sum];
										echo "<tr>
										<td><a href='../returnp/report?number=$move[number]'> Vozvrat sənədi ($move[number]) $move[datetime] tarixdən ($return->nameProduct, say $return->quantity)</a></td>
										<td></td>	
										<td></td>
										<td>$move[sum]</td>
										<td>$debt</td>
										<td>$move[note]</td>				
									</tr>";
								
							
							
							
						}
						
						else 
						{
							
							{
								$debt=round($debt+$move[debt],2);
								$move[debt]=-$move[debt];
								$sum=round($sum+$move[debt],2);
								echo "<tr>
								<td>Ödənib  $move[datetime] tarixdən</td>
								<td></td>	
								<td>$move[debt]</td>
								<td></td>	
								<td>$debt</td>
								<td>$move[note]</td>				
								</tr>";
							}
						
						}
						
					}
				}
				
					echo "<tr  class='danger'>
								<td>Итог</td>
								<td>$sum_debt</td>	
								<td>$sum</td>
								<td>$sum_voz</td>
								<td>$debt</td>
								<td></td>		
								</tr>";
		?>
        </tbody>
  </div>
