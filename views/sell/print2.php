<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
use \app\models\Product;
use yii\helpers\ArrayHelper;

use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArrivalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Arrivals';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div id="inf"  align='center'  style="width:400px;">
 <div align='center' style=" ">   <p style="margin-bottom:0px;font-size:28pt;"><b> Babanın Yeri </b></p>
												
    <p style="font-size:16pt;" >Mob: 055 663 01 01 </br>Tarix: <?php echo date("Y-m-d H:i:s"); ?><br>Çekin nömrəsi: <?php echo $number; ?><br/>
	 Masa: <?= \app\models\Place::findOne($place)->name ?><br><?= \app\models\Users::findOne($user)->fio ?> </p>
</div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'headerRowOptions' => ['class' => 'info'],
            //'showFooter' => TRUE,
            'showPageSummary' => true,
			
            'pageSummaryRowOptions' => ['class' => 'text-right danger','style'=>' text-align:left'],
			'showFooter' => true,
			'footerRowOptions' => ['style' => 'font-weight:bold;color:red;font-size:16pt;'],

            'tableOptions' => [

                'style' => 'font-size:14pt;border:2px solid;width:315px;    '

            ],
			'rowOptions'=>
			[  'style' => 'border:2px solid' ],
            'columns' => [
                [  'attribute' =>'id_menu',
                    'value' =>  'idMenu.name',
                    'enableSorting' => false,
                    'pageSummary' => 'Cəmi',
                    'width' => '170px',
					'footer' => 'Güzəşt'

                ],
                [
                    'attribute' => 'quantity',
                    'format' => 'raw',
                    'label' =>'Say',
                    'value' => 'quantity',
                    'enableSorting' => false,
                  
                    'hAlign' => 'right',
                    'width' => '20px',
					'footer' => $discount."%"
                ],
                [
                    'attribute' => 'price',
                    'format' => 'raw',
                    'value' => 'price',
                    'label' =>'Qiym.',
					 'encodeLabel' => false,
                    'enableSorting' => false,
                    'hAlign' => 'right',
                    'width' => '10px',
					'footer' => 'Yekun'
                   
                ],

                [
                  
                    'value' => 'sumValue',
					'label' => 'Cəmi',
                    'enableSorting' => false,
                    'pageSummary' => true,
                    'hAlign' => 'right',
                    'width' => '10px',
					'footer' => $sum
                ],

             


            ],
        ]); ?>
     

    </table>
	
	
    <p align='center'  class="test" style="font-size:16pt;">Bizi seçdiyiniz üçün təşəkkürlər! </br>Nuş olsun! </p>
    </div>
    




<?php
$script = <<< JS
 window.print();

$("#chek").modal("hide");  
			   

JS;
$this->registerJs($script);
?>