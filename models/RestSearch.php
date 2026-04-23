<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Arrival;

/**
 * ArrivalSearch represents the model behind the search form about `app\models\Arrival`.
 */
class RestSearch extends Arrival
{
    public $date_start;
    public $date_end;
    public $type;
    public $type_name;
    public $sumsell;

    public $name_product;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_user', 'id_store', 'received', 'id_contr', 'number', 'discount', 'returnp', 'rest','postponed'], 'integer'],
            [['quantity', 'price', 'usd', 'pricesell', 'sum'], 'number'],
            [['datetime','date_start','date_end', 'id_product','type','sumsell','name_product','type_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        //$query1=Arrival::find()->
        $query = Arrival::find()->select('arrival.id as id,sum(rest) as rest,arrival.id_product,,id_contr')
            ->where(['received' => '1'])->andWhere("postponed=0 || postponed IS NULL")->groupBy(['id_product'])->orderBy("id_product");

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'key' => function($model){
                return $model->id;
            },
            'pagination' => [

                'pageSize' =>'100',
            ],
        ]);


       
 $query->joinWith(['idProduct']);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_user' => $this->id_user,
            'product.id_type' => $this->type_name,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'usd' => $this->usd,
            'pricesell' => $this->pricesell,
            'sum' => $this->sum,
            'id_store' => $this->id_store,
            'received' => $this->received,
            'postponed' =>$this->postponed,
            'datetime' => $this->datetime,
            'id_contr' => $this->id_contr,
          //  'id_product' => $this->id_product,
            'number' => $this->number,
            'discount' => $this->discount,
            'returnp' => $this->returnp,
            'rest' => $this->rest,

        ]);
          
		      $query->andFilterWhere(['like', 'menu.name', $this->id_product]);
             $query->andFilterWhere(['like', 'menu.name', $this->name_product]); 
        return $dataProvider;
    }
}
