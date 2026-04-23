<?php

namespace app\controllers;

use app\models\Sell;
use Yii;
use app\models\Transfer;
use app\models\Arrival;
use app\models\TransferSearch;
use app\models\RestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\TypeProduct;
use app\models\Product;

/**
 * TransferController implements the CRUD actions for Transfer model.
 */
class TransferController extends Controller
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
        ];
    }

    /**
     * Lists all Transfer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransferSearch(['received' => 0,'id_user'=> Yii::$app->user->identity->id_user]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transfer model.
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
     * Creates a new Transfer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transfer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Transfer model.
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
     * Deletes an existing Transfer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Transfer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transfer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transfer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionFind()
    {

        $searchModel = new RestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderAjax('find', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }


    public function actionInsert($id, $quantity)
    {


        $model = new Transfer();
        $model->id_product = $id;
        $model->id_user = Yii::$app->user->identity->id_user;
        $model->quantity = $quantity;

        $model->received = 0;
        $model->save();




    }
    public function actionDeleteAll()
    {
        $model = new Transfer();
        $model->deleteAll(['received' => 0,'id_user'=> Yii::$app->user->identity->id_user]);
    }
    public function actionUpdateQuantity($id, $quantity)
    {
        $model = $this->findModel($id);
        $model->quantity = $quantity;
        $model->save();

    }

    public function actionReceived($whence, $where,$date)
    {
        $mes="";
        $max = Arrival::find()->select('number')->max('number');
        if ($max) $max = $max + 1;
        else $max = 1;

        $number = Transfer::find()->select('number')->max('number');
        if ($number) $number = $number + 1;
        else $number = 1;
      foreach (Transfer::find()->where(['received' => 0,'id_user' =>Yii::$app->user->identity->id_user ])->all() as $row) {

            $model=Arrival::find()
              ->select("sum(rest) as rest")
              ->where(["id_spr"=>$row->id_product,'id_store'=>$whence])
              ->orderBy([
                  'date' => SORT_DESC
              ])
              ->one();
            if ($model->rest>=$row->quantity)
            {
               foreach (Arrival::find()->where(["id_spr"=>$row->id_product,'id_store'=>$whence])
                    ->orderBy([
                        'date' => SORT_DESC
                    ])->all() as $arr)
                {
                   //
                    if ($row->quantity<=$arr->rest){
                        $arrival = new Arrival();
                        $arrival->id_spr = $row->id_product;
                        $arrival->quantity = $row->quantity;
                        $arrival->rest = $row->quantity;
                        $arrival->price =$arr->price;
                        $arrival->pricesell =$arr->pricesell;
                        $arrival->sum = $row->quantity*$arr->price;
                        $arrival->id_user = Yii::$app->user->identity->id_user;
                        $arrival->id_store=$where;
                     //   $arrival->id_contr=1;
                        $arrival->date=$date;
                        $arrival->received = 1;
                        $arrival->number=$max;
                       $arrival->save();

                       $arr->rest=$arr->rest-$row->quantity;
                        $arr->save();
                        break;
                    }

                    else {
                        $arrival = new Arrival();
                        $arrival->id_spr = $row->id_product;
                        $arrival->quantity = $row->quantity;
                        $arrival->rest = $arr->rest;
                        $arrival->price =$arr->price;

                        $arrival->sum = $row->quantity*$arr->price;
                        $arrival->id_user = Yii::$app->user->identity->id_user;
                        $arrival->pricesell =$arr->pricesell;
                        $arrival->id_store=$where;
                      //  $arrival->id_contr=1;
                        $arrival->date=$date;
                        $arrival->received = 1;
                        $arrival->number=$max;
                        $arrival->save();

                        $row->quantity=$row->quantity-$arr->rest;
                        $arr->rest=0;
                        $arr->save();
                    }

                }

                $row->received=1;
                $row->otkuda=$whence;
                $row->kuda=$where;
                $row->datetime=$date;
                $row->number=$number;
                $row->save();

            }
            else {
                $data[0]=1;
                $name=(new Transfer())->getGegName($row->id_product);
                $data[1]=$name;
               return $name;
            }

        }



    }

    public function actionType1($id)
    {
        // $model=TypeProduct::find()->where(["id_parent" => $id])->all();
        return   " Grupa 2". Select2::widget([
            'data' =>  ArrayHelper::map(TypeProduct::find()->where(['id_parent' => $id])->all(), 'id', 'name'),
            'name' => 'type2',
            'options' => [
                'placeholder' => 'Seçin',

                'id'=>'type2',

            ]
        ]);
    }

    public function actionType2($id)
    {
        // $model=TypeProduct::find()->where(["id_parent" => $id])->all();
        return   " Grupa 3". Select2::widget([
            'data' =>  ArrayHelper::map(TypeProduct::find()->where(['id_parent' => $id])->all(), 'id', 'name'),
            'name' => 'type3',
            'options' => [
                'placeholder' => 'Seçin',

                'id'=>'type3',

            ]
        ]);
    }
    public function actionType3($id)
    {


        return " Grupa 4" . Select2::widget([
            'data' => ArrayHelper::map(TypeProduct::find()->where(['id_parent' => $id])->all(), 'id', 'name'),
            'name' => 'type4',
            'options' => [
                'placeholder' => 'Seçin',

                'id' => 'type4',

            ]
        ]);

    }
    public function actionType5($id)
    {

        return   " Grupa 5". Select2::widget([
            'data' =>  ArrayHelper::map(TypeProduct::find()->where(['id_parent' => $id])->all(), 'id', 'name'),
            'name' => 'type5',
            'options' => [
                'placeholder' => 'Seçin',

                'id'=>'type5',

            ]
        ]);
    }

    public function actionType4($id)
    {
        // $model=TypeProduct::find()->where(["id_parent" => $id])->all();
        return    Select2::widget([
            'data' => ArrayHelper::map(Product::find()->where(["id_type" => $id])->all(), 'id', 'name'),
            'name' => 'product',
            'options' => [
                'placeholder' => 'Seçin',

                'id'=>'product',

            ]
        ]);
    }

    public function actionCheck($id){
        $model=TypeProduct::find()->where(["id_parent" => $id])->one();
        if ($model->id) { return 0;}
        else { return 1;}

    }

    public function actionReport()
    {


        $searchModel = new TransferSearch(['received' => '1']);
       $searchModel->date_start = date('Y-m-d');
       $searchModel->date_end = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider=ArrivalSearch::find()->all();
        return $this->render('report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



}
