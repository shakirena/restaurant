<?php

namespace app\controllers;

use app\models\Debt;
use app\models\Exchange;
use app\models\Postponed;
use app\models\Menu;
use app\models\ProductSearch;
use app\models\MenuSearch;
use app\models\Sell;
use app\models\Balance;
use app\models\Product;
use Yii;
use app\models\Arrival;
use app\models\ArrivalSearch;
use app\models\ArrivalIndexSearch;
use app\models\RestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\TypeProduct;
use app\models\Barcodep;
use yii\filters\AccessControl;
/**
 * ArrivalController implements the CRUD actions for Arrival model.
 */
class ArrivalController extends Controller
{
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
			'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','report','edit'],
                'rules' => [
                    [
                       
                        'allow' => true,
                        'roles' => ['@'],
                    ],
					
                ],
           
            ],
        ];
    }

    /**
     * Lists all Arrival models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArrivalIndexSearch(['received' => 0,'id_user'=> Yii::$app->user->identity->id_user,'postponed'=>0 ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $sum=Arrival::find()->select('sum(sum) as sum,sum(usd*quantity) as usd')->where(['received'=>'0','id_user'=> Yii::$app->user->identity->id_user,'postponed'=>0])->groupBy('number')->one();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sum'=> $sum->sum,
            'usd' => $sum->usd,

        ]);
    }

    /**
     * Displays a single Arrival model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Arrival model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Arrival();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    public function actionCancel($number)
	{

		
	
		
	
		$debt=Debt::find()->where(['number' => $number ])->one();
		if ($debt->id) $debt->delete();
		
		$model2 = new Arrival();
        $model2->updateAll(['received'=>0],['number' => $number]);



	}	
    /**
     * Updates an existing Arrival model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
         $product=Product::find()->where(["id" => $model->id_product])->one();
        if (Yii::$app->request->post()) {

            $model2 = new Arrival();
            $model2->updateAll(['rest'=>0],["id_product"=>$model->id_product,"id_store"=>$model->id_store]);
           
            $model=Arrival::find()->where(["id_product"=>$model->id_product,"id_store"=>$model->id_store])->orderBy("id DESC")->one();

            $product_post = Yii::$app->request->post("Product");
            $arrival = Yii::$app->request->post("Arrival");
            $model->rest= $arrival["rest"];
            $model->price= $arrival["price"];
            $model->save();

            
            $product->name=$product_post["name"];
            $product->save();
			
		
           
          
            return $this->redirect('edit');
        } else {
           
            return $this->render('update', [
                'model' => $model,
                'product' =>$product,
               
            ]);
        }
    }

    /**
     * Deletes an existing Arrival model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $arrival=Arrival::find()->where(["id"=>$id])->one();
        $product=Product::find()->where(["id"=>$arrival->id_product])->one();
        $product->delete();
       // $this->findModel($id)->delete();
        $searchModel = new RestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('edit', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
    public function actionDelete2($id)
    {

         $this->findModel($id)->delete();
        $searchModel = new ArrivalSearch(['received' => 0,'id_user'=> Yii::$app->user->identity->id_user,'postponed'=>0 ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $sum=Arrival::find()->select('sum(sum) as sum,sum(usd*quantity) as usd')->where(['received'=>'0','id_user'=> Yii::$app->user->identity->id_user,'postponed'=>0])->groupBy('number')->one();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sum'=> $sum->sum,
            'usd' => $sum->usd

        ]);

    }

    public function actionDeleteAll()
    {
        $model = new Arrival();
        $model->deleteAll(['received' => 0,'id_user'=> Yii::$app->user->identity->id_user,'postponed'=>0]);
    }
    /**
     * Finds the Arrival model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Arrival the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Arrival::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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

    public function actionChangeProcent($proc)
    {

        foreach(Arrival::find()->where(['received' => 0, 'postponed' => 0, 'id_user'=>Yii::$app->user->identity->id_user])

            ->all() as $arrival)
        {
            $arrival->price=$arrival->price-$proc*$arrival->price/100;
            $arrival->sum= $arrival->price*$arrival->quantity;
            $arrival->discount=round(( $arrival->pricesell- $arrival->price)*100/ $arrival->price,2);
            $arrival->save();

        }
    }
public function actionGetPricesell($id)
{
    $arrival=Arrival::find()
        ->select('pricesell,price')
        ->where(['id_product' => $id])
        ->orderBy([
            'id' => SORT_DESC
        ])
        ->one();
    if ($arrival->pricesell) $pricesell=$arrival->pricesell;
    else $pricesell=0;

    return $pricesell;
}

    public function actionGetPrice($id)
    {
        $arrival=Arrival::find()
            ->select('pricesell,price')
            ->where(['id_product' => $id])
            ->orderBy([
                'id' => SORT_DESC
            ])
            ->one();
        if ($arrival->price) $price=$arrival->price;
        else $price=0;

        return $price;
    }

    public function actionGetProcent($id)
{
    $arrival=Arrival::find()
        ->select('pricesell,price')
        ->where(['id_product' => $id])
        ->orderBy([
            'id' => SORT_DESC
        ])
        ->one();
    $proc=($arrival->pricesell-$arrival->price)*100/$arrival->price;

    return round($proc,1);
}
    public function actionGetProcentEdit($price,$pricesell)
    {

        $proc=($pricesell-$price)*100/$price;

        return round($proc,1);
    }
    public function actionProcentEdit($procent,$price)
    {

        $pricesell=round($price+$procent*$price/100,2);

        return $pricesell;
    }
    public function actionInsert( $quantity,$id)
    {

        $arrival = new Arrival();
        $arrival->id_product = $id;
        $arrival->quantity = $quantity;
        $arrival->rest = $quantity;
        $arrival->price =0;  
        $arrival->sum = $quantity*$price;
        $arrival->id_user = Yii::$app->user->identity->id_user;
        $arrival->received = 0;
        $arrival->save();
		
		print_r($arrival);


    }

    public function actionInsertType(array $id)
    {
        $sell=Arrival::find()->where(['received' => 0,'id_user'=>Yii::$app->user->identity->id_user,'postponed'=>0])->one();
        if ($sell->number) $max=$sell->number;

        else {
            $max = Arrival::find()->select('number')->max('number');
            if ($max) $max = $max + 1;
            else $max = 1;
        }
        foreach ($id as $id_product) {
            $arrival = Arrival::find()
                ->select('pricesell')
                ->where(['id_product' => $id_product])
                ->orderBy([
                    'datetime' => SORT_DESC
                ])
                ->one();
            if ($arrival->pricesell) $pricesell = $arrival->pricesell;
            else $pricesell = 0;
            $arrival = new Arrival();
            $arrival->id_product = $id_product;
            $arrival->quantity = 1;
            $arrival->rest = 1;
            $arrival->price = 0;
            $arrival->number=$max;
            $arrival->pricesell = $pricesell;
            // $arrival->usd=$usd;
            $arrival->sum = 0;
            $arrival->id_user = Yii::$app->user->identity->id_user;
            $arrival->received = 0;
            //t_r($arrival);
            $arrival->save();
            print_r($arrival);
        }

    }

    public function actionInsertBarcode($barcode)
    {
        $product=Barcodep::find()
            ->select("id_product")
            ->where(["name"=>$barcode])
            ->one();
        if ($product->id_product) {
            $arrival1=Arrival::find()
                ->select('pricesell,price,discount,pack,price_top')
                ->where(['id_product' => $product->id_product,'received'=>1])
                ->orderBy([
                    'id' => SORT_DESC
                ])
                ->one();
            if ($arrival1->pricesell) $pricesell=$arrival1->pricesell;
            else $pricesell=0;
            if ($arrival1->price) $price=$arrival1->price;
            else $price=0;
            $arrival = new Arrival();
            $arrival->pricesell =$pricesell;
            $arrival->price = $price;
            $arrival->discount = $arrival1->discount;
            $arrival->id_product = $product->id_product;
            $arrival->quantity = 1;
            if ($arrival1->pack) $pack=$arrival1->pack;
            else $pack=1;
            if ($arrival1->price_top) $price_top=$arrival1->price_top;
            else $price_top=$pricesell;
            $arrival->pack =$pack ;
            $arrival->price_top =$price_top ;
            $arrival->rest =1;
            $arrival->sum =  $arrival->quantity * $arrival->price ;
            $arrival->id_user = Yii::$app->user->identity->id_user;
            $arrival->received = 0;
            $arrival->save();
        }

    }

    public function actionUpdateQuantity($id, $quantity)
    {
        $model = $this->findModel($id);
        $model->quantity = $quantity;
        $model->rest = $quantity;
        $model->sum = $quantity * $model->price;
        $model->save();

        return  $model->sum ;
    }

    public function actionUpdatePrice($id, $price)
    {
        $model = $this->findModel($id);
        $model->price = $price;
        $model->discount=round(($model->pricesell-$price)*100/$price,2);
        $model->sum = $price * $model->quantity;
        $model->save();
        return  $model->sum ;
        
    }
   

    public function actionUpdatePack($id, $price)
    {
        $model = $this->findModel($id);
        $model->pack = $price;
        $model->save();

    }
	 public function actionUpdateDiscount($id, $price)
    {
        $model = $this->findModel($id);
        $model->endirim = $price;
		$model->price =$model->price-($model->price*$price)/100;
		$model->sum = $model->sum-($price*$model->sum)/100;
        $model->save();

    }
    public function actionUpdateUsd($id, $price)
    {
        $model = $this->findModel($id);
        $model->usd = $price;
       // $rate=Exchange::find()->one();
      //  $azn=$price * $rate->rates;
      //  $model->sum = $azn * $model->quantity;
      //  $model->price=$azn;
        $model->save();

    }
    public function actionUpdatePricesell($id, $price)
    {
        $model = $this->findModel($id);
        $model->pricesell = $price;
        $model->discount=round(($price-$model->price)*100/$model->price,2);
        $model->save();

    }
   public function actionUpdatePriceTop($id, $price)
    {
        $model = $this->findModel($id);
        $model->price_top = $price;
        $model->save();

    }
	  public function actionUpdatePriceOpt($id, $price)
    {
        $model = $this->findModel($id);
        $model->price_opt = $price;
        $model->save();

    }
    public function actionUpdateProc($id, $proc)
    {
        $model = $this->findModel($id);
        $model->pricesell = round($model->price+$proc*$model->price/100,2);
        $model->discount=$proc;
        $model->save();

    }
public function actionInserBarcodSell(){
foreach (Barcodep::find()->andWhere("name=''")->all() as $row) {
  $barcode="";
$barcode.=rand(1,9);
for($i=0;$i<=4;$i++)
{
	$barcode.=rand(0,9);
}

    $row->name=$barcode;
    $row->save();}

}

public function actionTest(){
foreach (Barcodep::find()->all() as $row) {

  foreach (Barcodep::find()->andWhere("name='$row->name' and name!='развесной' and name!='tərəzi'  and id_product!='$row->id_product'   and id>$row->id")->all() as $row1) {
			$arrival1=Arrival::find()->select("sum(rest) as  rest")->where(['id_product' => $row1->id_product])->one();
			$arrival2=Arrival::find()->where(['id_product' => $row->id_product])->orderBy("id DESC")->one();
			if ($arrival1->rest) {
					if ($arrival2->rest) {
								$arrival2->rest=$arrival2->rest+$arrival1->rest;
								$arrival2->save();}
			
			else  {
			$arrival1->id_product=$arrival2->id_product;
			$arrival1->save();
			
			}
			}
			echo $row->name.":".$arrival1->rest." , ".$arrival2->rest."   ";
			$row1->delete();
			//echo $row1->id.",";
				//echo $row1->name;
				//$row1->delete();
				//$this->actionTest();
			//$row1->save();
			//print_r($row1);
			
		
  }
echo '--------------<br>';	
}
}
    public function actionReceived($store,$contractor, $date)
    {

        $max = Arrival::find()->select('number')->max('number');
        if ($max) $max = $max + 1;
        else $max = 1;

        $row=Arrival::find()->select("sum(sum) as sum,datetime")->where(['received' => 0,'id_user' =>Yii::$app->user->identity->id_user,'postponed'=>0 ])->one();
        if( $row->sum>0) {
            $debt = new Debt();
            $debt->id_user = Yii::$app->user->identity->id_user;
			$debt->debt =$row->sum;
            $debt->datatime = $date." ".date("H:i:s");;
            $debt->number = $max;
            $debt->id_contr = $contractor;
            $debt->discount = 0;
            $debt->save();
        }
		
	
       if ($row->datetime) $date=$row->datetime;
	   else $date=date("Y-m-d H:i:s");
        $model = new Arrival();
        $model->updateAll(['id_store' => $store,'id_contr'=>$contractor, 'datetime' => $date, 'received' => 1, 'number' => $max], ['received' => 0,'id_user' =>Yii::$app->user->identity->id_user,'postponed'=>0]);
		Yii::$app->session->remove('contractor');

    }

    public function actionMpdf()
    {

        $searchModel = new ArrivalSearch(['received' => 0,'id_user' =>Yii::$app->user->identity->id_user,'postponed'=>0]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'received');


        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
            'content' => $this->renderPartial('print', [
                // 'searchModel' => $searchModel,
                'dataProvider' => $dataProvider]),
            'options' => [
                'title' => 'MARKET',
                'subject' => 'Generating PDF files via yii2-mpdf extension has never been easy'
            ],
            'methods' => [
                'SetHeader' => ['Generated On: ' . date("r")],
                'SetFooter' => false,
            ]
        ]);

        return $pdf->render();
    }

    public function actionReport()
    {


        $searchModel = new ArrivalSearch(['received' => '1']);
        $searchModel->date_start = date('Y-m-d')." 00:00:00";
        $searchModel->date_end = date('Y-m-d')." 23:59:59";

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //$dataProvider=ArrivalSearch::find()->all();
        return $this->render('report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionReport1($number)
    {


        $searchModel = new ArrivalSearch(['received' => '1']);
        $searchModel->number=$number;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //$dataProvider=ArrivalSearch::find()->all();
        return $this->render('report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionTable($type1,$type2,$type3,$type4,$type5,$product,$date1,$date2)
    {

        $searchModel = new ArrivalSearch(['received' => '1']);
        $searchModel->date_start = $date1;
        $searchModel->date_end = $date2;
        if ($product!='null') {
            $find=explode(",",$product);
            $searchModel->id_product = $find;
        }
        else {
            if ($type5)
            {
                $searchModel->type = $type5;

            }
            else
                if ($type4)
                {

                    $type=TypeProduct::find()->where(["id_parent"=>$type4])->one();
                    if ($type->id) {
                        foreach(TypeProduct::find()->where(["id_parent"=>$type4])->all() as $name3)
                        {
                            $find[]= $name3->id;
                        }
                    }
                    else  $find[]= $type4;
                    $searchModel->type = $find;

                }
                else
                    if ($type3)
                    {

                        $type=TypeProduct::find()->where(["id_parent"=>$type3])->one();
                        if ($type->id) {
                            foreach (TypeProduct::find()->where(["id_parent" => $type3])->all() as $name2) {
                                $type = TypeProduct::find()->where(["id_parent" => $name2->id])->one();
                                if ($type->id) {
                                    foreach (TypeProduct::find()->where(["id_parent" => $name2->id])->all() as $name3) {
                                        $find[] = $name3->id;
                                    }
                                } else  $find[] = $name2->id;

                            }
                        } else  $find[] = $type3;

                        $searchModel->type = $find;

                    }
                    else
                        if ($type2)
                        {
                            $type=TypeProduct::find()->where(["id_parent"=>$type2])->one();
                            if ($type->id) {
                                foreach(TypeProduct::find()->where(["id_parent"=>$type2])->all() as $name1)
                                {

                                    $type=TypeProduct::find()->where(["id_parent"=>$name1->id])->one();
                                    if ($type->id) {
                                        foreach (TypeProduct::find()->where(["id_parent" => $name1->id])->all() as $name2) {
                                            $type = TypeProduct::find()->where(["id_parent" => $name2->id])->one();
                                            if ($type->id) {
                                                foreach (TypeProduct::find()->where(["id_parent" => $name2->id])->all() as $name3) {
                                                    $find[] = $name3->id;
                                                }
                                            } else  $find[] = $name2->id;

                                        }
                                    }
                                    else  $find[] = $name1->id;

                                }
                            }
                            else  $find[] = $type2;
                            //  print_r($find);
                            $searchModel->type = $find;

                        }
                        else
                            if ($type1)
                            {

                                foreach(TypeProduct::find()->where(["id_parent"=>$type1])->all() as $name1)
                                {
                                    $type=TypeProduct::find()->where(["id_parent"=>$name1->id])->one();
                                    if ($type->id) {
                                        foreach (TypeProduct::find()->where(["id_parent" => $name1->id])->all() as $name2) {
                                            $type=TypeProduct::find()->where(["id_parent"=>$name2->id])->one();
                                            if ($type->id) {
                                                foreach (TypeProduct::find()->where(["id_parent" => $name2->id])->all() as $name3) {
                                                    $type = TypeProduct::find()->where(["id_parent" => $name3->id])->one();
                                                    if ($type->id) {
                                                        foreach (TypeProduct::find()->where(["id_parent" => $name3->id])->all() as $name4) {
                                                            $find[] = $name4->id;
                                                        }
                                                    } else  $find[] = $name3->id;

                                                }
                                            } else  $find[] = $name2->id;
                                        }
                                    } else  $find[] = $name1->id;

                                }

                                $searchModel->type=$find;
                            }
        }

        $searchModel->date_start = $date1;
        $searchModel->date_end = $date2;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider=ArrivalSearch::find()->all();

        return $this->render('report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionVozvrat()
    {


        $searchModel = new ArrivalSearch();
        $searchModel->date_start = date('Y-m-d');
        $searchModel->date_end = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider=ArrivalSearch::find()->all();
        return $this->render('report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionReturn()
    {

        $searchModel = new RestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('return', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
    public function actionReceivedReturn($number)
    {
        $arrival=new Arrival();
        $arrival->deleteAll(['number' => $number]);

        $debt=new Debt();
        $debt->deleteAll(['number' => $number]);

       /* foreach (Arrival::find()->where(["id_product"=>$id])->andWhere(" rest>0")->orderBy("datetime DESC")->all()as $row)
        {
           if($quantity<=$row->rest) {
               $row->rest=$row->rest-$quantity;
               $row->returnp=$row->returnp+$quantity;
               $row->save();

               $debt=Debt::find()->where(['number'=>$row->number])->one();
               $debt->debt=$debt->debt-$row->price*$quantity;
               $debt->sum=$debt->sum-$row->price*$quantity;
               $debt->save();
               break;
           }
            else {
                $row->returnp=$row->rest;
                $quantity=$quantity-$row->rest;
                $row->rest=0;
                $row->save();



            }

        }*/

    }
    public function actionReceivedReturn1($id,$quantity)
    {
        foreach (Arrival::find()->where(["id_product"=>$id])->andWhere(" rest>0")->orderBy("datetime DESC")->all()as $row)
         {
            if($quantity<=$row->rest) {
                $row->rest=$row->rest-$quantity;
                $row->returnp=$row->returnp+$quantity;
                $row->save();

                $debt=Debt::find()->where(['number'=>$row->number])->one();
                $debt->debt=$debt->debt-$row->price*$quantity;
                $debt->sum=$debt->sum-$row->price*$quantity;
                $debt->save();
                break;
            }
             else {
                 $row->returnp=$row->rest;
                 $quantity=$quantity-$row->rest;
                 $row->rest=0;
                 $row->save();



             }

         }

    }
   public function actionRest()
    {


        $searchModel = new RestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $param=Yii::$app->request->queryParams["RestSearch"]["id_store"];
        if ($param)
            if (Yii::$app->request->queryParams["RestSearch"]["type"])
                $sum=Arrival::find()->select("sum(rest) as rest,sum(price*rest) as sum,sum(rest*pricesell) as sumsell")->where(['id_store'=>$param,'id_type'=>Yii::$app->request->queryParams["RestSearch"]["type"]])->one();
                else $sum=Arrival::find()->select("sum(rest) as rest,sum(price*rest) as sum,sum(rest*pricesell) as sumsell")->where(['id_store'=>$param])->one();
        else
            if (Yii::$app->request->queryParams["RestSearch"]["type"])
                $sum=Arrival::find()->select("sum(rest) as rest,sum(price*rest) as sum,sum(rest*pricesell) as sumsell")->where(['id_type'=>Yii::$app->request->queryParams["RestSearch"]["type"]])->one();
            else $sum=Arrival::find()->select("sum(rest) as rest,sum(price*rest) as sum,sum(rest*pricesell) as sumsell")->one();
        //$dataProvider=ArrivalSearch::find()->all();
        return $this->render('rest', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'rest_sum' => round($sum->rest,2),
            'sum_sum' => round($sum->sum,2),
            'sum_sumsell'=>round($sum->sumsell,2)
        ]);
    }

    public function actionEdit()
    {


        $searchModel = new RestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('edit', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTableEdit($type1,$type2,$type3,$type4,$type5, $product)
    {


        $searchModel = new RestSearch();


        if ($product!='null') {
            $find=explode(",",$product);
            $searchModel->id_product = $find;
        }
        else {
            if ($type5)
            {
                $searchModel->type = $type5;

            }
            else
                if ($type4)
                {

                    $type=TypeProduct::find()->where(["id_parent"=>$type4])->one();
                    if ($type->id) {
                        foreach(TypeProduct::find()->where(["id_parent"=>$type4])->all() as $name3)
                        {
                            $find[]= $name3->id;
                        }
                    }
                    else  $find[]= $type4;
                    $searchModel->type = $find;

                }
                else
                    if ($type3)
                    {

                        $type=TypeProduct::find()->where(["id_parent"=>$type3])->one();
                        if ($type->id) {
                            foreach (TypeProduct::find()->where(["id_parent" => $type3])->all() as $name2) {
                                $type = TypeProduct::find()->where(["id_parent" => $name2->id])->one();
                                if ($type->id) {
                                    foreach (TypeProduct::find()->where(["id_parent" => $name2->id])->all() as $name3) {
                                        $find[] = $name3->id;
                                    }
                                } else  $find[] = $name2->id;

                            }
                        } else  $find[] = $type3;

                        $searchModel->type = $find;

                    }
                    else
                        if ($type2)
                        {
                            $type=TypeProduct::find()->where(["id_parent"=>$type2])->one();
                            if ($type->id) {
                                foreach(TypeProduct::find()->where(["id_parent"=>$type2])->all() as $name1)
                                {

                                    $type=TypeProduct::find()->where(["id_parent"=>$name1->id])->one();
                                    if ($type->id) {
                                        foreach (TypeProduct::find()->where(["id_parent" => $name1->id])->all() as $name2) {
                                            $type = TypeProduct::find()->where(["id_parent" => $name2->id])->one();
                                            if ($type->id) {
                                                foreach (TypeProduct::find()->where(["id_parent" => $name2->id])->all() as $name3) {
                                                    $find[] = $name3->id;
                                                }
                                            } else  $find[] = $name2->id;

                                        }
                                    }
                                    else  $find[] = $name1->id;

                                }
                            }
                            else  $find[] = $type2;
                            //  print_r($find);
                            $searchModel->type = $find;

                        }
                        else
                            if ($type1)
                            {

                                foreach(TypeProduct::find()->where(["id_parent"=>$type1])->all() as $name1)
                                {
                                    $type=TypeProduct::find()->where(["id_parent"=>$name1->id])->one();
                                    if ($type->id) {
                                        foreach (TypeProduct::find()->where(["id_parent" => $name1->id])->all() as $name2) {
                                            $type=TypeProduct::find()->where(["id_parent"=>$name2->id])->one();
                                            if ($type->id) {
                                                foreach (TypeProduct::find()->where(["id_parent" => $name2->id])->all() as $name3) {
                                                    $type = TypeProduct::find()->where(["id_parent" => $name3->id])->one();
                                                    if ($type->id) {
                                                        foreach (TypeProduct::find()->where(["id_parent" => $name3->id])->all() as $name4) {
                                                            $find[] = $name4->id;
                                                        }
                                                    } else  $find[] = $name3->id;

                                                }
                                            } else  $find[] = $name2->id;
                                        }
                                    } else  $find[] = $name1->id;

                                }

                                $searchModel->type=$find;
                            }
        }


        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('edit', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    public function actionTableRest($type1,$type2,$type3,$type4,$type5, $product)
    {


        $searchModel = new RestSearch();


        if ($product!='null') {
           $find=explode(",",$product);
            $searchModel->id_product = $find;
        }
        else {
            if ($type5)
            {
                $searchModel->type = $type5;

            }
            else
                if ($type4)
                {

                    $type=TypeProduct::find()->where(["id_parent"=>$type4])->one();
                    if ($type->id) {
                        foreach(TypeProduct::find()->where(["id_parent"=>$type4])->all() as $name3)
                        {
                            $find[]= $name3->id;
                        }
                    }
                    else  $find[]= $type4;
                    $searchModel->type = $find;

                }
                else
                    if ($type3)
                    {

                        $type=TypeProduct::find()->where(["id_parent"=>$type3])->one();
                        if ($type->id) {
                            foreach (TypeProduct::find()->where(["id_parent" => $type3])->all() as $name2) {
                                $type = TypeProduct::find()->where(["id_parent" => $name2->id])->one();
                                if ($type->id) {
                                    foreach (TypeProduct::find()->where(["id_parent" => $name2->id])->all() as $name3) {
                                        $find[] = $name3->id;
                                    }
                                } else  $find[] = $name2->id;

                            }
                        } else  $find[] = $type3;

                        $searchModel->type = $find;

                    }
                    else
                        if ($type2)
                        {
                            $type=TypeProduct::find()->where(["id_parent"=>$type2])->one();
                            if ($type->id) {
                            foreach(TypeProduct::find()->where(["id_parent"=>$type2])->all() as $name1)
                            {

                                $type=TypeProduct::find()->where(["id_parent"=>$name1->id])->one();
                                if ($type->id) {
                                    foreach (TypeProduct::find()->where(["id_parent" => $name1->id])->all() as $name2) {
                                        $type = TypeProduct::find()->where(["id_parent" => $name2->id])->one();
                                        if ($type->id) {
                                            foreach (TypeProduct::find()->where(["id_parent" => $name2->id])->all() as $name3) {
                                                $find[] = $name3->id;
                                            }
                                        } else  $find[] = $name2->id;

                                    }
                                }
                                else  $find[] = $name1->id;

                            }
                            }
                            else  $find[] = $type2;
                     //     print_r($find);
                            $searchModel->type = $find;

                        }
                        else
                            if ($type1)
                            {

                                foreach(TypeProduct::find()->where(["id_parent"=>$type1])->all() as $name1)
                                {
                                    $type=TypeProduct::find()->where(["id_parent"=>$name1->id])->one();
                                    if ($type->id) {
                                        foreach (TypeProduct::find()->where(["id_parent" => $name1->id])->all() as $name2) {
                                            $type=TypeProduct::find()->where(["id_parent"=>$name2->id])->one();
                                            if ($type->id) {
                                                foreach (TypeProduct::find()->where(["id_parent" => $name2->id])->all() as $name3) {
                                                    $type = TypeProduct::find()->where(["id_parent" => $name3->id])->one();
                                                    if ($type->id) {
                                                        foreach (TypeProduct::find()->where(["id_parent" => $name3->id])->all() as $name4) {
                                                            $find[] = $name4->id;
                                                        }
                                                    } else  $find[] = $name3->id;

                                                }
                                            } else  $find[] = $name2->id;
                                        }
                                    } else  $find[] = $name1->id;

                                }

                                $searchModel->type=$find;
                            }
        }


        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $param=Yii::$app->request->queryParams["RestSearch"]["id_store"];
        if ($param)
            if (Yii::$app->request->queryParams["RestSearch"]["type"])
                $sum=Arrival::find()->select("sum(rest) as rest,sum(price*rest) as sum,sum(rest*pricesell) as sumsell")->where(['id_store'=>$param])->one();
            else $sum=Arrival::find()->select("sum(rest) as rest,sum(price*rest) as sum,sum(rest*pricesell) as sumsell")->where(['id_store'=>$param])->one();
        else
            if (Yii::$app->request->queryParams["RestSearch"]["type"])
                $sum=Arrival::find()->select("sum(rest) as rest,sum(price*rest) as sum,sum(rest*pricesell) as sumsell")->one();
            else $sum=Arrival::find()->select("sum(rest) as rest,sum(price*rest) as sum,sum(rest*pricesell) as sumsell")->one();
        //$dataProvider=ArrivalSearch::find()->all();
        return $this->render('rest', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'rest_sum' => round($sum->rest,2),
            'sum_sum' => round($sum->sum,2),
            'sum_sumsell'=>round($sum->sumsell,2)
        ]);
    }

    public function actionUsdAzn($usd){
        $rate=Exchange::find()->one();
        $price=$usd * $rate->rates;
        echo $price;
    }

    public  function actionExchange($rate){
        $exchange=Exchange::find()->one();
        $exchange->rates=$rate;
        $exchange->date=date("Y-m-d H:i:s");
        $exchange->save();
    }

    public function actionPostponedArrival($store,$contractor){
        $model2 = new Arrival();
        $model2->updateAll(['postponed'=>1,'id_contr'=>$contractor,'id_store'=>$store],['postponed'=>0,'received' => 0,'id_user' =>Yii::$app->user->identity->id_user ]);


    }

    public function actionPostponed()
    {
        $searchModel = new ArrivalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where('postponed <> 0');
        return $this->render('postponed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,


        ]);
    }


    public function actionPostponedSelect($number)
    {
        $model2 = new Arrival();
        $model2->updateAll(['postponed'=>1],['postponed'=>0,'received' => 0,'id_user' =>Yii::$app->user->identity->id_user ]);

        $model2 = new Arrival();
        $model2->updateAll(['postponed'=>0],['number' => $number]);


        $searchModel = new ArrivalSearch(['received' => 0,'id_user'=> Yii::$app->user->identity->id_user,'postponed'=>0 ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $sum=Arrival::find()->select('sum(sum) as sum,sum(usd*quantity) as usd')->where(['received'=>'0','id_user'=> Yii::$app->user->identity->id_user,'postponed'=>0])->groupBy('number')->one();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sum'=> $sum->sum,
            'usd' => $sum->usd

        ]);
    }
}
