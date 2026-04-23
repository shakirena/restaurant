<?php

namespace app\controllers;

use app\models\Arrival;
use app\models\Client;
use app\models\Contractor;
use app\models\Sell;
use app\models\Store;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\helpers\Url;
use app\models\ProductSearch;
use yii\helpers\ArrayHelper;
use app\models\Product;
use app\models\ClientSearch;
use kartik\select2\Select2;
use app\models\TypeProduct;
use app\models\Debt;
use app\models\Dclient;
use app\models\Costs;
use app\models\TypeCosts;
use DateTime;
use app\models\Kassa;
class MoveController extends Controller
{
   // public $layout = 'start';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionContr()
    {
        return $this->render('contr');
    }
    public function actionFind()
    {

        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderAjax('find', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
	    public function actionCloseDay()
    {
        $model = new Sell();
        $datetime = new DateTime();
        $h = (int) $datetime->format('H');
        if ($h >= 0 && $h <= 8) {
            $datetime->modify('-1 day');
            $date = $datetime->format('Y-m-d');
        }
        else $date = $datetime->format('Y-m-d');
        $model->updateAll(["date"=>$date],["date" => NULL]);
		$model = new Costs();
		$model->updateAll(["date"=>$date],["date" => NULL]);
		$model = new Dclient();
		$model->updateAll(["date"=>$date],["date" => NULL]);

		$model = new Debt();
		$model->updateAll(["date"=>$date],["date" => NULL]);


    }
    public function actionReport1($id,$date1,$date2,$type)
    {
	   $date1.=' 00:00:00';
        $date2.=' 23:59:59';
		
	if ($type==1) {
				$model=Debt::find()->where(["id_contr" =>$id])->andWhere("datatime>='$date1' AND datatime<='$date2'")->orderBy("datatime ASC")->all();
				$debt=Debt::find()->select("sum(debt) as sum,sum(sum_usd) as sum_usd")->where(["id_contr" =>$id])->one();
				$current=Debt::find()->select("sum(debt) as sum,sum(sum_usd) as sum_usd")->where(["id_contr" =>$id])->andWhere("datatime<'$date1'")->one();
				$client=Contractor::find()->where(["id" =>$id])->one()->name;
				return $this->render('report_contre1', [
					'model' => $model,
					'debt'=> $debt,
					'current'=>$current,
					'contractor' => $client
				]);
	}
     else 
	   	$model=Debt::find()->andWhere("datatime>='$date1' AND datatime<='$date2'")->where(["id_contr" =>$id])->orderBy("datatime ASC")->all();
				$debt=Debt::find()->select("sum(debt) as sum,sum(sum_usd) as sum_usd")->where(["id_contr" =>$id])->one();
				$current=Debt::find()->select("sum(debt) as sum,sum(sum_usd) as sum_usd")->where(["id_contr" =>$id])->andWhere("datatime<'$date1'")->one();
				$client=Contractor::find()->where(["id" =>$id])->one()->name;
				return $this->render('report_contre', [
					'model' => $model,
					'debt'=> $debt,
					'current'=>$current,
					'contractor' => $client
				]);

    }


    public function actionClient()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('client', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionClientShow()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderAjax('client1', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReportClient($id,$type,$date1,$date2)
    {
        $date1.=' 00:00:00';
        $date2.=' 23:59:59';
         $client=Client::find()->where(["id_client" =>$id])->one()->fio;
        if ($type==1) {
						$model=Dclient::find()->where(["id_client" =>$id])->andWhere("datetime>='$date1' AND datetime<='$date2'")->orderBy("datetime ASC")->all();
						$debt=Dclient::find()->select("sum(debt) as sum")->where(["id_client" =>$id])->one();
						$current=Dclient::find()->select("sum(debt) as sum,id")->andWhere(["id_client" =>$id])->andWhere("datetime<'$date1'")->one();
						if ($current->id) $current=$current->sum;
						else $current=0;
						$prixod = Costs::find()->select("sum(sum) as sum")->where(['id_client' => $id,'id_kassa' =>1,'id_type' =>3])->andWhere("datetime<'$date1'")->one()->sum;
						$rasxod = Costs::find()->select("sum(sum) as sum")->where(['id_client' => $id,'id_kassa' =>1,'id_type' =>2])->andWhere("datetime<'$date1'")->one()->sum;
						return $this->render('report_client', [
							'model' => $model,
							'debt'=> $debt,
							'current'=>$current,
							'client' => $client
						]);
								
            }
         if ($type==2) {
						$model=Dclient::find()->andWhere("datetime>='$date1' AND datetime<='$date2'")->where(["id_client" =>$id])->orderBy("datetime ASC")->all();
						$debt=Dclient::find()->select("sum(debt) as sum,sum(usd) as usd")->where(["id_client" =>$id])->one();
						$current=Dclient::find()->select("sum(debt) as sum,sum(usd) as usd")->where(["id_client" =>$id])->andWhere("datetime<'$date1'")->one();
			      return $this->render('report_client2', [
                             'model' => $model,
							'debt'=> $debt,
							'current'=>$current,
							'client' => $client
                           ]);
       }

    }
  
    public function actionItog()
    {

		if (Yii::$app->request->get('prixod')) {
		 if (!$id = TypeCosts::find()->where(['type'=>1])->one()->id) 
		 { 
			$model = new TypeCosts();
			$model->type = 1;
			$model->name="Medaxil";
			$model->save();
			$id = $model->id;
		 }
			$costs = new Costs();
			$costs->id_type = $id;
			$costs->sum = Yii::$app->request->get('prixod');
			$costs->datetime = date("Y-m-d H:i:s");
			$costs->save();
			
		}
		if (Yii::$app->request->get('date1'))
		{
			 $this->redirect(['itog-received','date1'=>Yii::$app->request->get('date1'),'date2'=>Yii::$app->request->get('date2')]);
			
		
		}
		
        return $this->render('itog', [
           
        ]);

    }

    public function actionItogReceived($date1,$date2)
    {
		
	
		$date11=$date1;
        $date22=$date2;
		
		//$date1.=' 00:00:00';
        //$date2.=' 23:59:59';
		$pos=Costs::find()->select("sum(sum) as sum")->andWhere("date>='$date1' AND date<='$date2' AND  id_type= 3")->one()->sum;
		
		$sum=Sell::find()->select("sum(sum) as sum")->andWhere("(date>='$date1' AND date<='$date2') OR date IS NULL")->one()->sum;
		$oplata=$sum-Dclient::find()->select("sum(debt) as debt")->andWhere("((date>='$date1' AND date<='$date2') OR date IS NULL) AND debt>0 and number IS NOT NULL")->one()->debt;
		$dolg=Dclient::find()->select("sum(debt) as debt")->andWhere("((date>='$date1' AND date<='$date2') OR  date IS NULL) AND debt<0 AND sum IS NULL")->one()->debt;
		$return=Dclient::find()->select("sum(sum) as sum")->andWhere("((date>='$date1' AND date<='$date2') OR date IS NULL) AND  sum<0")->one()->sum;
		
		$dclient=Dclient::find()->select("sum(debt) as debt")->andWhere("((date>='$date1' AND date<='$date2') OR date IS NULL) AND debt>0")->one()->debt;
		$debt=Debt::find()->select("sum(debt) as debt")->andWhere("((date>='$date1' AND date<='$date2') OR date IS NULL) AND debt<0 AND number IS NULL")->one()->debt;
		$costs=Costs::find()->joinWith(["idType"])->select("sum(sum) as sum")->andWhere("((date>='$date1' AND date<='$date2') OR date IS NULL) AND type_costs.type=0")->one()->sum;
		$prixod=Costs::find()->joinWith(["idType"])->select("sum(sum) as sum")->andWhere("((date>='$date1' AND date<='$date2') OR date IS NULL) AND type_costs.type = 1")->one()->sum;
		
		$kassa = Costs::find()->select("sum(sum) as sum")->where(['id_kassa' => 1 ])->one()->sum;
		$name = Kassa::find()->where(['id' => 1 ])->one()->name;
        
        return $this->render('itog', [
            'sum' => $sum,
			'oplata'=>$oplata,
            'dolg' => $dolg,
			'return' => $return,
			'date1'=>$date11,
			'date2'=>$date22,
			'costs'=>$costs,
			'dclient'=>$dclient,
			'prixod'=>$prixod,
			'debt'=>$debt,
			'kassa' => $kassa,
			'name' => $name,
			"pos" => $pos
        ]);

    }
    
}
















































































































































































































































































































