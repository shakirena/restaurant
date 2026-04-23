<?php

namespace app\controllers;

use app\models\Type;
use Yii;
use app\models\Menu;
use app\models\MenuSearch;
use app\models\ConsistSearch;
use app\models\Consist;
use app\models\Arrival;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
{
    /**
     * @inheritdoc
     */
    public $layout='admin';
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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menu model.
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
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu();

        $post=Yii::$app->request->post("Menu");
        $searchModel = new ConsistSearch();
        $searchModel->id_user=Yii::$app->user->identity->id_user;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where('id_menu IS NULL');
        if ( $post) {
            $type=Menu::find()->where(["id_type"=>$post["id_type"]])->orderBy("vid DESC")->one();
            if ($type->vid)
            {
                $vid=$type->vid+1;
                $vid=str_repeat("0", 5-strlen($vid)).$vid;
            }
            else{
                $row=Type::find()->where(["id"=>$post["id_type"]])->one();
                $vid=$row->vid;
                $vid=str_repeat("0", 2-strlen($vid)).$vid.'000';
            }
            $model->name=$post["name"];
            $model->vid=$vid;
            $model->price=$post["price"];
			 $model->price_vip=$post["price_vip"];
            $model->id_type=$post["id_type"];
            $model->id_store=$post["id_store"];
            $model->barcode=$post["barcode"];

            $model->save();
            $consist=new Consist();

            $consist->updateAll(["id_menu"=>$model->id], ["id_user"=>Yii::$app->user->identity->id_user,'id_menu'=>NULL]);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }
    public function actionUpdateQuantityConsist($id,$quantity)
    {
        $model=Consist::findOne($id);
        $model->quantity=$quantity;
        $model->save();
        $price = Arrival::find()->where(["id_product" => $model->id_product])->orderBy("id DESC")->one()->price;
        return round($price * $model->quantity,2);;

    }
    public function actionDeleteConsist($id)
    {
        $model=Consist::findOne($id);

        $model->delete();
        return true;

    }
    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $searchModel = new ConsistSearch();
        $searchModel->id_menu=$id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
	
        } else {


            return $this->render('update', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
	
    public function actionInsertProduct($id_product,$id_menu)
    {
        $model=new Consist();
        $model->id_product=$id_product;
        if ($id_menu!=-1)   $model->id_menu=$id_menu;
        $model->id_user=Yii::$app->user->identity->id_user;
        $model->quantity=1;
        $model->save();
        print_r($model);
        return true;

    }
	public function actionChangeDisabled($id)
	{
		$model=Menu::findOne(['id'=>$id]);
		if ($model->disabled==0) $model->disabled=1;
		else $model->disabled=0;
	
		$model->save();
	}

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
