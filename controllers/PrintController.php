<?php

namespace app\controllers;

use app\models\Client;
use Yii;
use app\models\Spares;
use app\models\TypeSp;
use app\models\SellSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SparesController implements the CRUD actions for Spares model.
 */
class PrintController extends Controller
{
    /**
     * @inheritdoc
     */
    public $layout = 'start';

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
     * Lists all Spares models.
     * @return mixed
     */
    public function actionReceived()
    {
        $searchModel = new SellSearch(['sold'=>0,'id_client' =>Yii::$app->session->get("id")]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderAjax('..\client\view3', [
            'model' => Client::find()->where(['id' => Yii::$app->session->get("id")])->one(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
