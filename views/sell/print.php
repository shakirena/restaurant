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

<div id="inf" style="width:350px; margin-left:10px">
 <div align='center' style=" margin-left:-4em">   <p style="margin-bottom:0px;font-size:28pt;"><b> Babanın Yeri </b></p>
												
    <p style="font-size:12pt;" >Masa: <?= \app\models\Place::findOne($place)->name ?> <br><?= \app\models\Users::findOne($user)->name ?> </p>
</div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'headerRowOptions' => ['class' => 'info'],
            //'showFooter' => TRUE,
            'showPageSummary' => true,
            'pageSummaryRowOptions' => ['class' => 'text-right danger'],
            //'footerRowOptions' => ['class' => 'info'],
            'tableOptions' => [

                'style' => 'font-size:12pt;border:2px solid;width:330px;    '

            ],
			'rowOptions'=>
			[  'style' => 'border:2px solid' ],
            'columns' => [
                [  'attribute' =>'id_menu',
                    'value' =>  'idMenu.name',
                    'enableSorting' => false,
                    'pageSummary' => 'Yekun',
                    'width' => '170px'
                ],
                [

                    'format' => 'raw',
                    'label' =>'Say',
                    'value' => 'quantityPrint',
                    'enableSorting' => false,
                    'pageSummary' => true,
                    'hAlign' => 'right',
                    //'width' => '20px'
                ]

             


            ],
        ]); ?>
     

    </table>
   

</div>


<?php
/*$script = <<< JS
 $(document).ready(function(){
 var PDF = document.getElementById("plugin");

   PDF.focus();
      PDF.contentWindow.print();
});


JS;
$this->registerJs($script);*/
?>
<?php
$script = <<< JS
 window.print();

$("#chek").modal("hide");


JS;
$this->registerJs($script);
?>
