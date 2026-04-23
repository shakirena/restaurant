<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use app\models\Kassa;;
use kartik\date\DatePicker;
use app\models\Client;
use app\models\Contractor;
use app\models\Spares;
use app\models\Users;
use app\models\Sell2;
use kartik\select2\Select2;
use app\components\PushAll;
$placei = Yii::$app->session->get('place');

/* @var $this yii\web\View */
/* @var $searchModel app\models\SellSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->params['breadcrumbs'][] = $this->title;
//if (  Yii::$app->user->identity->id_role==1)  $role=0;

//else $role=1;
?>
<div class="sell-index" xmlns="http://www.w3.org/1999/html">
   
        <div class="col-md-8">
            
              <?php
				
                        foreach ($models as $id => $model)
                        {
                           
                                
                                    $sell = Sell2::find()->andWhere("id_user!= Yii::$app->session->get('user')")->where(['sold' => 0, 'id_place' => $id])->one();
									$ofis= Users::find()->where(["id_user"=>$sell->id_user])->one()->fio;

                                    if ($sell->id_place)
										  echo "<button title=$ofis class='btn btn-danger btn-lg' disabled onclick='selectPlace($id)' style='margin:20px;width: 160px;'>$model</button>";
										    else
                                            echo "<button  class='btn btn-default btn-lg' onclick='selectPlace($id)' style='margin:20px;width: 160px;'>$model</button>";

                                
                          


                        }
                      





                ?>


        </div>
     


</div>
