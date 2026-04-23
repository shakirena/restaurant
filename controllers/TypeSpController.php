<?php

namespace app\controllers;

use Yii;
use app\models\TypeSp;
use app\models\TypeSpSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TypeSpController implements the CRUD actions for TypeSp model.
 */
class TypeSpController extends Controller
{
    /**
     * @inheritdoc
     */
    public $layout = 'admin';
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
     * Lists all TypeSp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TypeSpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TypeSp model.
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
     * Creates a new TypeSp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TypeSp();

        if (Yii::$app->request->post()) {
            $post=Yii::$app->request->post("TypeSp");
            if ($post['cat']) {
                $row=TypeSp::find()->where(["cat"=>$post['cat']])->one();
                if ($row->vid)
                {
                    $vid=$row->vid+1;
                    $vid=str_repeat("0", 4-strlen($vid)).$vid;
                }
                else {
                    $row=TypeSp::find()->where(["id"=>$post['cat']])->one();
                    $vid=$row->vid+1;
                    $vid=str_repeat("0", 4-strlen($vid)).$vid;
                }
                $type=new TypeSp();
                $type->name=$post['name'];
                $type->vid=$vid;
                $type->cat=$post['cat'];
                $type->save();
            }

            else {
                $row=TypeSp::find()->where(["cat"=>0])->orderBy(" vid DESC")->one();
                    if ($row->vid)
                    {
                        $vid=$row->vid+100;
                        $vid=str_repeat("0", 4-strlen($vid)).$vid;
                    }
                    else
                    {
                        $vid="0100";

                    }
                $type=new TypeSp();
                $type->name=$post['name'];
                $type->vid=$vid;
                $type->cat=0;
                $type->save();
            }
           // print_r();
           return $this->redirect(['index']);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TypeSp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TypeSp model.
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
     * Finds the TypeSp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TypeSp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TypeSp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
