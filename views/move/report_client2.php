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
use app\models\Sell;
use app\models\Arrival;
use app\models\Transfer;
use app\models\Client;
use app\models\Menu;
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
        <th>Say</th>
        <th>Borc</th>
        <th>Ödənilib</th>
        <th>Vozvrat</th>
        <th>Yekun qalıq</th>
        </thead>
        <tbody>
        <?php echo "<tr><td colspan='6' class='danger'>Müştəri: $client</td> </tr>"; ?>
        <?php
        $sum=0;
            foreach($model as $move) {

                if ($move['flag']==0) {
                    $sum=$sum+$move[sum];
                    $move[sum]=round($move[sum],2);
                    $sum=round($sum,2);
                    $product=Menu::find()->where(["id" =>$move[id_menu]])->one()->name;
                    echo "<tr>
                                        <td><a href='../sell/report1?number=$move[number]'>  $product ($move[date] tarixdən)</a></td>
                                       <td>$move[quantity]</td>
                                        <td>$move[sum]</td>
                                        <td></td>
                                      <td></td>

                                        <td>$sum</td>";
                }
                if ($move['flag']==1) {


                    if ($move['id_type']==15) {
                        $sum=$sum-$move[sum];
                        $move[sum]=round($move[sum],2);
                        $sum=round($sum,2);
                        if ($sum<0) $sum=0;
                        $returnp=\app\models\Returnp::find()->where(["number" =>$move[number]])->one();
                        $product=Spares::find()->where(["id" => $returnp->id_product])->one()->name;
                        echo "<tr>
                                        <td> $product ($move[date] tarixdən)</td>
 <td> $returnp->quantity</td>
                                        <td></td>
                                        <td></td>
                                         <td>$move[sum]</td>


                                        <td>$sum</td>";
                    }
                    else {
                        $sum=$sum-$move[sum];
                        $move[sum]=round($move[sum],2);
                        $sum=round($sum,2);
                        echo "<tr>
                                        <td>$move[date] tarixdən</td>
 <td></td>
                                        <td></td>
                                         <td>$move[sum]</td>
                                         <td></td>
                                        <td>$sum</td>
                                       ";
                    }

                }


            }?>
        </tbody>
  </div>
