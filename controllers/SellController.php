<?php

namespace app\controllers;


use app\models\Menu;
use app\models\Place;
use app\models\Byuer;
use Yii;
use DateTime;
use app\models\Sell;
use app\models\Consist;
use app\models\Sell2;
use app\models\Returnp;
use app\models\Client;
use app\models\ClientSearch;
use app\models\Balance;
use app\models\Dclient;
use app\models\DclientSearch;
use app\models\SellSearch;
use app\models\Sell2Search;
use app\models\SellPrintSearch;
use app\models\MenuSearch;
use app\models\SparesSearch;
use yii\web\Controller;
use app\models\Type;
use app\models\TypePlace;
use app\models\Users;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Arrival;
use app\models\SellProdSearch;
use app\models\SellConsistSearch;
use app\models\SellDocSearch;
use app\models\Costs;
use app\models\Password;
use yii\filters\AccessControl;
/**
 * SellController implements the CRUD actions for Sell model.
 */
class SellController extends Controller
{
    /**
     * @inheritdoc
     */
    public $layout;
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST','GET'],
                ],
            ],
			
			'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','report','dialog','postponed','place','places', 'print','reportProd','reportDoc'],
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
     * Lists all Sell models.
     * @return mixed
     */
    public function actionEditSession($type)
    {
        if ($type=="check") Yii::$app->session->set('insert', '1');

    }
    public function actionPlace()
    {

        foreach (Place::find()->all() as $model)
        {
            $models[$model->id]=$model->name;
        }
        return $this->render('place', [
        
              'models' =>$models,
             
              
          ]);

    }
    public function actionPlaces()
    {

        foreach (Place::find()->all() as $model)
        {
            $models[$model->id]=$model->name;
        }

        Yii::$app->session->remove('user');

        return $this->render('places', [
        
              'models' =>$models,
        ]);

    }
	public function actionClickQuantity($quantity)
    {
		
		Yii::$app->session->set('quantity',$quantity);
	
	}
   public function actionIndex()
    {
     
        $gruppa=Type::find()->all();
        $data = Yii::$app->request->get('type', 'tarif');
		
        if ($data=="users")
        {


           foreach (Users::find()->where("id_role!=2")->all() as $model)
           {
               $models[$model->id]=$model->fio;
           }
            $next='place';
        }
       /* if ($data=="check" and !Yii::$app->session->get('insert'))
        {


           foreach (Users::find()->all() as $model)
           {
               $models[$model->id]=$model->fio;
           }
            $next='place';
        }*/
        if ($data=="place")
        {
           if ($id = Yii::$app->request->get('id')) Yii::$app->session->set('tarif',$id);

            foreach (Place::find()->all() as $model)
            {
                $models[$model->id]=$model->name;
            }
            $next='type';
            $back='tarif';
        }
		
		  if ($data=="tarif")
        {
          
		if ($id = Yii::$app->request->get('id')) Yii::$app->session->set('user',$id);
		else  Yii::$app->session->set('user',Yii::$app->user->identity->id_user);
          $models[0]="Standart";
		  $models[1]="VIP";
            $next='place';
            $back='type';
        }
        if ($data=="type")
        {

            if ($id = Yii::$app->request->get('id')) Yii::$app->session->set('place',$id);

            foreach (Type::find()->all() as $model)
            {
                $models[$model->id]=$model->name;
            }
            $back='place';
            $next='menu';

            $searchModel = new Sell2Search(['sold'=>0,'id_place'=> Yii::$app->session->get('place')]);
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $sum=Sell2::find()->select('sum(sum) as sum,id_place,discount, id_user,number,id_user')->where(['sold'=>'0','id_place'=> Yii::$app->session->get('place')])->groupBy('number')->one();
            if (!Yii::$app->session->get('user')) 
                if ( $sum->id_user) 
                    Yii::$app->session->set('user', $sum->id_user);
                    else 
                        Yii::$app->session->set('user',Yii::$app->user->identity->id_user);

        }
        if ($data=="menu")
        {
            $id = Yii::$app->request->get('id', Yii::$app->session->get('type'));
            Yii::$app->session->set('type',$id);

            foreach (Menu::find()->where(["id_type"=>$id , 'disabled' => 0])->all() as $model)
            {
                $models[$model->id]=$model->name;
            }
            $back='type';
            $next='check';

            $searchModel = new Sell2Search(['sold'=>0,'id_place'=> Yii::$app->session->get('place')]);
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $sum=Sell2::find()->select('sum(sum) as sum,id_place,discount,number,id_user')->where(['sold'=>'0','id_place'=> Yii::$app->session->get('place')])->groupBy('number')->one();


        }
        if ($data=="check" )
        {

            $id = Yii::$app->request->get('id', '1');
            if (Yii::$app->session->get('insert'))
            {
                    $menu=Menu::find()
                        ->select('price, price_vip')
                        ->where(['id' => $id])
                        ->one();
                    $sell=Sell2::find()->where(['sold' => 0,'id_place'=> Yii::$app->session->get('place')])->orderBy("id DESC")->one();
                    if ($sell->number)
                
                            $max=$sell->number;
                        else {
                                $sell=Sell2::find()->orderBy("id DESC")->one();
                                if ($sell->number) { $max = Sell2::find()->select('number')->max('number');
                                $max1 = Sell::find()->select('number')->max('number');
                                if ($max>$max1) $max = $max + 1; else $max=$max1+1;}
                                else 
                                {
                                $max = Sell::find()->select('number')->max('number');
                                $max = $max + 1;
                                }
                        }

                        $sell=Sell2::find()->where(["id_menu"=>$id,'id_place'=> Yii::$app->session->get('place'), 'sold'=>0])->one();
                        if ($sell->id) {
                            $sell->quantity=$sell->quantity+Yii::$app->session->get('quantity');
                            $sell->sum=$sell->quantity*$sell->price;
                            $sell->save();
                        }
                        else {
							print_r( Yii::$app->session->get('tarif'));
                            $sell = new Sell2();
                            $sell->id_menu = $id;
                            $sell->id_place =Yii::$app->session->get('place');
                            $sell->id_user = Yii::$app->session->get('user');
                            $sell->quantity = Yii::$app->session->get('quantity');;
							if ( Yii::$app->session->get('tarif') == 1 && $menu->price_vip >0)
                            $sell->price =$menu->price_vip;
						else  $sell->price =$menu->price;
                            $sell->sum = $sell->quantity * $sell->price;
                            $sell->datetime=date("Y-m-d H:i:s");
                            $sell->sold = 0;
                            $sell->print =0;
                            $sell->number=$max;
                            $sell->id_store=Yii::$app->session->get("store");
                            $sell->save();
						

                        }
            }
            foreach (Menu::find()->where(["id_type"=>  Yii::$app->session->get('type') ])->all() as $model)
            {
                $models[$model->id]=$model->name;
            }

            $back='type';
            $next='check';

            $searchModel = new Sell2Search(['sold'=>0,'id_place'=> Yii::$app->session->get('place')]);
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $sum=Sell2::find()->select('sum(sum) as sum,id_place,discount,number,id_user')->where(['sold'=>'0','id_place'=> Yii::$app->session->get('place')])->groupBy('number')->one();
            Yii::$app->session->remove('insert');
			
        }
        if (Yii::$app->user->identity->id_role==1 ) $this->layout = 'sell';
		Yii::$app->session->set('quantity',1);
        return $this->render('index', [
          'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sum' => $sum->sum,
			'discount' => $sum->discount,
            'place' => $place,
            'current'=> $sum->id_place,
            'models' =>$models,
            'next' =>$next,
            'back'=>$back,
			 'type'=>$data,
			 'user' =>$sum->id_user,
			 'number' => $sum ? $sum->number : '-1'
			
        ]);
    }
	
  
    public function actionChangePlace($id)
    {
        $model = new Sell2();
        $model->updateAll(['id_place'=>$id],['id_place' => Yii::$app->session->get('place')]);
        Yii::$app->session->set('place',$id);
    }
	public function actionChangeDiscount($place,$discount)
	{
		
		foreach (Sell2::find()->where(['id_place'=>$place])->orderBy("id DESC")->all() as $model)
		 {
			$model->sum = $model->price *$model->quantity;
			$model->discount = $discount;
			$model->sum =round($model->sum - $model->sum/100 *$discount,2);
			$model->save();
		 
		 }
      
        
           
	}
    public function actionPostponed()
    {
        $searchModel = new Sell2Search();


        //  $searchModel->date_start = date('Y-m-d')." 00:00:00";
        //   $searchModel->date_end = date('Y-m-d')." 23:59:59";
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere("postponed!=0 and returnp=0");
        return $this->render('postponed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,


        ]);
    }
    public function actionPrint($number)
    {
        $searchModel = new Sell2Search(['number' => $number]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //  $client=Client::find()->where(['id_client' =>   Yii::$app->session->get('id_client')])->one();
        $sum=Sell2::find()->select('sum(sum) as sum,number,datetime,discount,id_user')->where(['number' => $number])->one();
        //  $user=Users::find()->where(["id_user"=>Yii::$app->request->get('user')])->one()->fio;

        return $this->renderAjax('print2',[
            'dataProvider' => $dataProvider,
            'money' => Yii::$app->request->get('money'),
            'sum' => round($sum->sum,2),
			'discount' => $sum->discount,
            'number' => $sum->number,
			'user'=>$sum->id_user,
			'place'=>Yii::$app->session->get('place')
			
        ]);

    }
	
	public function actionItogReceived($date1,$date2,$product)
    {

		$date1.=' 00:00:00';
        $date2.=' 23:59:59';
		if ($product) {
        $sell=Sell::find()->select("sum(sum) as sum")->joinWith(['idMenu.idProduct'])->andWhere("datetime>='$date1' AND datetime<='$date2' AND product.id=$product")->one()->sum;
		$arrival=Arrival::find()->select("sum(sum) as sum")->joinWith(['idProduct'])->andWhere("datetime>='$date1' AND datetime<='$date2' AND product.id=$product")->one()->sum;
		}
		else 
		{
			 $sell=Sell::find()->select("sum(sum) as sum")->andWhere("datetime>='$date1' AND datetime<='$date2'")->one()->sum;
			$arrival=Arrival::find()->select("sum(sum) as sum")->andWhere("datetime>='$date1' AND datetime<='$date2' ")->one()->sum;
		
			
		}

        return $this->render('itog', [
            'sell' => $sell,
			'arrival'=>$arrival,
           
        ]);

    }
		public function actionItog()
    {

		
        return $this->render('itog', [
           
        ]);

    }
	
	    public function actionPrint1($number)
    {
        $searchModel = new SellSearch(['number' =>$number]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->pagination->pageSize=200;
        //  $client=Client::find()->where(['id_client' =>   Yii::$app->session->get('id_client')])->one();
        $sum=Sell::find()->select('sum(sum) as sum,number,datetime,id_byuer,id_place,id_user')->where(['number' =>$number])->one();
        //  $user=Users::find()->where(["id_user"=>Yii::$app->request->get('user')])->one()->fio;

        return $this->renderAjax('print2',[
            'dataProvider' => $dataProvider,
            'money' => Yii::$app->request->get('money'),
            'sum' => $sum->sum,
            'number' => $sum->number,
			'byuer'=> $sum->id_byuer,
			'place'=> $sum->id_place,
			'user'=> $sum->id_user,
			'date'=> $sum->datetime
        ]);

    }
	public function actionPrintOtmena($id,$reason,$quantity)
    {
		$model = Sell2::find()->where(['id' => $id])->one();
		
		$sum=Sell2::find()->select('sum(sum) as sum,number,datetime,id_user,id_place')->where(['id' => $id])->one();

		
		return $this->renderAjax('print-otmena',[
          
			'model' => $model,
			'reason' => $reason,
			'quantity' =>$quantity,
			'sum' => $sum->sum,
			'id_place' => $sum->id_place,
			'id_user' => $sum->id_user,

        ]);
	
	}
    public function actionPrint3()
    {
        $searchModel = Sell2::find()->where(['sold' => 0, 'id_user' =>  Yii::$app->session->get('user'),'id_place' => Yii::$app->session->get('place')])->andWhere("print!=quantity")->all();

        $sum=Sell2::find()->select('sum(sum) as sum,number,datetime,id_user,id_place')->where(['sold' => 0, 'id_user' =>  Yii::$app->session->get('user'),'id_place' => Yii::$app->session->get('place')])->one();

		print_r( $searchModel->query);
        return $this->renderAjax('print3',[
          
			'searchModel' => $searchModel,
            'money' => Yii::$app->request->get('money'),
            'sum' => $sum->sum,
			'id_place' => $sum->id_place,
			'id_user' => $sum->id_user,

        ]);

    }
	
	public function actionCheckPlace($id)
	{
	
		$sell=Sell2::find()->where(["id_place"=>$id])->one();
		if ($sell->id) return 1;
		else return 0;
	
	}
		  public function actionPrintDay()
    {
        $start=date("Y-m-d")." 00:00:00";
		$end=date("Y-m-d")." 23:59:59";
		$sell=Sell::find()->andWhere("datetime>='$start' and datetime<='$end'")->all();
        return $this->renderAjax('printDay',[
          'sell'=>$sell,
		'date'=>date("Y-m-d")
        ]);

    }
    public function actionCancel($number){

        $sell=new Sell2();
        $sell->updateAll(["postponed"=>0,'sold'=>0,"id_user"=>Yii::$app->user->identity->id_user], ["number"=>$number]);


    }
    /*
    public function actionFind()
    {

        $searchModel = new RestSearch();
        $searchModel->id_store=Yii::$app->session->get('store');

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderAjax('find', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
*/

    public function actionFind()
    {

        $searchModel = new MenuSearch();
		$searchModel->disabled = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderAjax('find', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
    /**
     * Displays a single Sell model.
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
     * Creates a new Sell model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sell();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Sell model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Sell model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model =Sell2::find()->where(['id'=>$id])->one();
        $model->delete();
        //return $this->redirect(['index']);
    }

    /**
     * Finds the Sell model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sell the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sell::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionInsert($id,$quantity)
    {
        $menu=Menu::find()
            ->select('price')
            ->where(['id' => $id])
            ->one();
        /* if ($sell->number)
                  if ($sell->postponed!=0) 	$max=$sell->number+1;
          else $max=$sell->number;*/

        $sell=Sell2::find()->where(['sold' => 0,'postponed'=>0, 'id_user'=>Yii::$app->user->identity->id_user])->orderBy("id DESC")->one();
        if ($sell->number)
            	{   
					$max =$sell->number;
                }
           
        else {
		
			   $sell=Sell2::find()->where(['sold' => 0])->orderBy("id DESC")->one();
			   if ($sell->number)
			   {
				    $max1 = Sell2::find()->select('number')->max('number');
					$max1 = $max1 + 1;
			   }
			 
				$max2 = Sell::find()->select('number')->max('number');
				if ($max2) $max2 = $max2 + 1;
				if ($max1>$max2) $max=$max1;
				else $max=$max2;
			
			
            
        }



        $sell=Sell2::find()->where(["id_menu"=>$id, 'id_user'=>Yii::$app->user->identity->id_user, 'sold'=>0,'postponed' => '0'])->one();
        if ($sell->id) {
            $sell->quantity=$sell->quantity+$quantity;
			$sell->sum=$sell->quantity*$sell->price;
            $sell->save();
        }
        else {
            $sell = new Sell2();
            $sell->id_menu = $id;
            $sell->id_user = Yii::$app->user->identity->id_user;
            $sell->quantity = $quantity;
            $sell->price =$menu->price;
            $sell->sum = $quantity*$menu->price;
            $sell->datetime=date("Y-m-d H:i:s");
            $sell->sold = 0;
            $sell->number=$max;
			$sell->print=0;
            $sell->id_store=Yii::$app->session->get("store");
            $sell->save();
        }


        var_dump( $sell);

    }

    public function actionUpdatePrice($id, $price)
    {
        $model =Sell2::find()->where(['id'=>$id])->one();
        $model->price = $price;
        $model->sum = $price * $model->quantity;
        $model->save();

    }


    public function actionUpdateQuantity($id, $quantity)
    {
        $model =Sell2::find()->where(['id'=>$id])->one();
		$model->quantity =$model->quantity + $quantity;
		/*$val = $model->quantity -  $model->print;
		if (!$val) 
			if ($quantity <  $model->quantity) return;*/
        $model->sum = $model->quantity * $model->price;
        if ($model->quantity>0) $model->save();



    }
    public function actionClient()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderAjax('client', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSelect($id)
    {
        Yii::$app->session->set('id_client',$id);
        $client=Client::find()
            ->select("fio")
            ->where(['id_client'=>$id])->one();
        Yii::$app->session->set('client',$client->fio);

    }
    public function actionOtmena($number)
    {
        $number1 = Sell2::find()->where(['sold' => 0, 'id_user' => Yii::$app->user->identity->id_user, 'postponed' => 0])->one()->number;
        if ($number1) {

        $sell = new Sell2();
        $sell->updateAll(["postponed" => 1], ["number" => $number1]);
        }
		$max = Returnp::find()->select('number')->max('number');
        if ($max) $max = $max + 1;
        else $max = 1;
		
        foreach (Sell::find()->where(['number' => $number])->all() as $row) {

            $sell = new Sell2();
            $sell->id_user = $row->id_user;
            $sell->id_menu = $row->id_menu;
            $sell->quantity = $row->quantity;
            $sell->price = $row->price;
            $sell->sold = 0;
            $sell->sum = $row->sum;
            $sell->datetime = $row->datetime;
            $sell->id_client = Yii::$app->session->get('id_client');
            $sell->number = $row->number;
			$sell->postponed = $row->quantity;
            $sell->id_place = $row->id_place;
            $sell->postponed = 0;
            $sell->save();

			
			
			$return=new Returnp();
            $return->id_product=$row->id_menu;
            $return->id_user=Yii::$app->session->get('user');
            $return->data=date("Y-m-d");
            $return->received=1;
            $return->quantity= $row->quantity;
            $return->number=$max;        
            $return->save();
			
			
            foreach (Consist::find()->where(["id_menu"=>$row->id_menu])->all() as $costs) 
            {
                $arrival=Arrival::find()->where(['id_product' => $costs->id_product])->orderBy(['id' => SORT_DESC])->one();  
                if ($arrival->id)
						{						
                            $arrival->rest=$arrival->rest + $row->quantity * $costs->quantity;
                            $arrival->save();
						}
                
            }
        
            $row->delete();
			Yii::$app->session->set('user',$row->id_user);
			Yii::$app->session->set('place', $row->id_place);
			
        }

       $dclient= Dclient::find()->where(["number" => $number])->one();
       if ($dclient) $dclient->delete();

    }
  public function actionReceived($money, $postponed,$kassa,$virtual,$number)
        {


            $money2 = $money;
            $client = Yii::$app->session->get('id_client');
            $max = Sell2::find()->where(['number' => $number])->one()->number;
            $model = new Sell2();
            $summa = 0;
            foreach ($model->find()->where(['number' => $number])->all() as $row) {
                $sum = $row->sum - $money;
                if ($sum < 0) $sum = 0;
                $money = $money - $row->sum;
                if ($money < 0) $money = 0;

                $summa = $summa + $row->sum;

                if ($postponed == 1) {
                    $quantity = $row->quantity;
                    $row->postponed = $row->quantity;
                    $row->print = $row->quantity;
                   // $row->id_place=Yii::$app->session->get('place');
                    $row->save();


                } else  {
                    $vse = 0;

                    foreach (Consist::find()->where(["id_menu"=>$row->id_menu])->all() as $consist) {
                        $store=Menu::findOne($row->id_menu)->id_store;
                        
                        $arrival = Arrival::find()
                            ->select('sum(rest) as rest')
                            ->where(['id_product' => $consist->id_product,'received'=>1])
                            ->groupBy('id_product')
                            ->one();
							if ($arrival){
								if ($arrival->rest>0 && $arrival->rest>=$consist->quantity* $row->quantity) {

									foreach (Arrival::find()->where(['id_product' => $consist->id_product,'received'=>1])->andWhere("rest>0")->orderBy(['datetime' => SORT_ASC])->all() as $arrival)
									{

										if ($consist->quantity <= $arrival->rest) {
											$arrival->rest = $arrival->rest - $consist->quantity* $row->quantity;
											$arrival->save();echo "mm";break;
										}

										else
										{
											$consist->quantity=$consist->quantity* $row->quantity- $arrival->rest;
											$arrival->rest = 0;
											$arrival->save();
											echo "s";
										}
									}
								}
								else
								{
									$arrival=Arrival::find()->where(['id_product' => $consist->id_product])->orderBy(['datetime' => SORT_DESC])->one();

									$arrival->rest = $arrival->rest - $consist->quantity;
									$arrival->save();

								}
					    }

                    }
						
                    $sell = new Sell();
                    $sell->id_user = $row->id_user;
                    $sell->id_menu= $row->id_menu;
                    $sell->quantity = $row->quantity;
                    $sell->price = $row->price;
					$sell->discount = $row->discount;
                    $sell->sold = 1;
                    $sell->sum = $row->sum;
                    $sell->datetime = $row->datetime;
                    $sell->id_client = Yii::$app->session->get('id_client');
                    $sell->number = $row->number;
                  
                    $sell->postponed = $row->quantity;


                    $sell->id_place = Yii::$app->session->get('place');
                    $sell->postponed = 0;

                   // print_r($sell);
                    $sell->save();
					print_r($sell);
                    // $row->delete();
                    $vse = 1;
                 
                }
                if ($postponed != 1) $row->delete();

            }


                if ($postponed!=1 && Yii::$app->session->get('id_client')!=1 &&  $summa>$money2) {
                    $sum=round($summa-$money2-$virtual,2);
					$dclient=new Dclient();
					
					  if ($money2+$virtual>$summa)      
					  {
						  $dclient->sum=$summa;
						  $dclient->debt=0;
					  }
					  else
					  {		  
						  $dclient->sum=$money2;
						  $dclient->debt=$sum;
                          $dclient->pos=$virtual;
                      
					  }
				   

					$dclient->number=$max;
					$dclient->id_client=Yii::$app->session->get('id_client');;
					$dclient->datetime=date("Y-m-d H:i:s");
					//$dclient->date_return=$date;
					$dclient->save();
                }
                if ($kassa ) {
                $cost = new Costs();
                  $cost->id_kassa = $kassa;
               /* if (Yii::$app->session->get('id_client')==1) 
                        {
                            if ($money2) $cost->sum = $money2;
                                else $cost->sum = $summa;
                        }
                else */
                $cost->sum = $virtual;
                $cost->fid = $max;
                $cost->id_type = 3;
                $cost->id_client = Yii::$app->session->get('id_bonus');
                $cost->id_user = Yii::$app->user->identity->id_user;
                $cost->datetime=date("Y-m-d H:i:s");
                $cost->save();
              
    
            }
            Yii::$app->session->set('quantity',1);
            Yii::$app->session->set('id_client', 1);
            Yii::$app->session->set('client', "Müştəri");
        }
    public function actionDialog()
    {
        $searchModel = new DclientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('dclient', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionDclientAdd()
    {
        $model = new Dclient();
        if (Yii::$app->request->post()) {
            $dclient = Yii::$app->request->post("Dclient");

      
            $model->id_client = $dclient['id_client'];
            $model->note = $dclient['note'];
			$model->debt = $dclient['debt'];
		
			$model->datetime = date("Y-m-d H:i:s");

           if($dclient['debt']>0) $model->save();
			return $this->redirect(['dialog']);
        }
        else {
            
            return $this->renderAjax('dclient_add', [
                'model' => $model,
            ]);
        }

    }
	public function actionInsertNote($id,$note)
	{
		$sell = Sell2::find()->where(['id' => $id])->one();
		$sell->note = $note;
		$sell->save();
	
	}
	public function actionGetNote($id)
	{
		$sell = Sell2::find()->where(['id' => $id])->one();
		return $sell->note;
	
	}
    public function actionInfoClient()
    {
        $searchModel = new ClientFioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('../move/client_sell', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' =>Yii::$app->request->get('type')
        ]);
    }

    public function actionDeleteAll()
    {
        $model = new Sell2();
        $model->deleteAll(['sold' => 0, 'id_user' =>  Yii::$app->session->get('user'),'id_place' => Yii::$app->session->get('place')]);
    }

    public function actionReceivedDebt($id,$sum,$note,$date,$kassa){
        $sum2=$sum;
        $dclient=Dclient::find()->where(["id_client" =>$id])->one();
        $sell=Sell::find()->where(['number'=>$dclient->number])->one()->id_user;         
                   
                 $dclient=new Dclient();
               $dclient->debt=-$sum;
                 
                   $dclient->id_client=$id;
                   $dclient->note=$note;
                   $dclient->datetime=$date.date(" H:i:s");
                   $dclient->save();
                 
                   
                   
                   $cost = new Costs();
                   $cost->id_kassa = $kassa;
                   $cost->sum = $sum;
                   $cost->fid = $dclient->id;
                   $cost->id_type = 1;
                   $cost->note = $note;
                   $cost->id_user = Yii::$app->user->identity->id_user;
                   $cost->datetime=date("Y-m-d H:i:s");
                   $cost->save();
                   
                   return $dclient->id;

    }

    public function actionReport1($number)
    {


        $searchModel = new SellSearch(['number' => $number]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider=ArrivalSearch::find()->all();
        return $this->render('report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	public function actionReportProd()
    {

        $searchModel = new SellProdSearch(['sold' => '1','postponed'=>0]);
        $searchModel->date_start = date('Y-m-d');
        $searchModel->date_end = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider=ArrivalSearch::find()->all();

        return $this->render('report_prod', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	public function actionReportDoc()
    {

        $searchModel = new SellDocSearch(['sold' => '1','postponed'=>0]);
		$searchModel->date_start = date('Y-m-d');
        $searchModel->date_end = date('Y-m-d');
		  $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('report_doc', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionReturn()
    {

        $searchModel = new SparesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('return', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
    public function actionReceivedReturn($id,$quantity,$reason)
    {

			$sell = Sell2::find()->where(['id' => $id])->one();
			$sell->print = $sell->print - $quantity;
			$sell->save();
              $max = Returnp::find()->select('number')->max('number');
             if ($max) $max = $max + 1;
             else $max = 1;
			$return=new Returnp();
             $return->id_product=$sell->id_menu;
             $return->id_user=Yii::$app->session->get('user');
             $return->reason=$reason;
             $return->data=date("Y-m-d");
             $return->received=1;
             $return->quantity=$quantity;
             $return->number=$max;
             $return->id_sell=$sell->number;
             $return->save();

			  $sell->quantity = $sell->quantity - $quantity;
			  if ($sell->quantity == 0) $sell->delete();
			  else {
			  $sell->sum =  $sell->quantity *  $sell->price;
			  $sell->save();}

    }

    public function actionReport()
    {

        $searchModel = new SellSearch(['sold' => '1','postponed'=>0]);

       $searchModel->date_start = date('Y-m-d');
       $searchModel->date_end = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionOtmenaForm($id)
    {
        $model = new Password();

		return $this->renderAjax('otmena', [
                'model' => $model,
				'id' =>$id
            ]);
        
    }
	
	 public function actionLogin()
    {
        $model = new Password();

		return $this->renderAjax('login', [
                'model' => $model,
            ]);
        
    }
	public function actionLoginPrice($id,$price)
    {
        $model = new Password();

		return $this->renderAjax('login-price', [
                'model' => $model,
				'id' => $id,
				'price' => $price
            ]);
        
    }
	public function actionLoginTransfer($id)
    {
        $model = new Password();

		return $this->renderAjax('login-transfer', [
                'model' => $model,
				'id' => $id
            ]);
        
    }
	public function actionSpaces($id)
    {
        $model = Place::find()->all();

		return $this->renderAjax('login-transfer', [
                'model' => $model,
				'id' => $id
            ]);
        
    }
	 public function actionLogin2()
    {
        $model = new Password();

		return $this->renderAjax('login2', [
                'model' => $model,
            ]);
        
    }
    public function actionConsist()
    {

        $searchModel = new SellConsistSearch(['sold' => '1','postponed'=>0]);

       $searchModel->date_start = date('Y-m-d')." 00:00:00";
        $searchModel->date_end = date('Y-m-d')." 23:59:59";
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider=ArrivalSearch::find()->all();

        return $this->render('consist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
