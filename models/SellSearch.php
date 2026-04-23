<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sell;
use DateTime;
/**
 * SellSearch represents the model behind the search form about `app\models\Sell`.
 */
class SellSearch extends Sell
{
    /**
     * @inheritdoc
     */
    public $date_start;
    public $date_end;
	public $name_product;
	public $id_type;
    public function rules()
    {
        return [
            [['id', 'id_user', 'sold', 'id_client', 'number', 'flag', 'id_store', 'id_place'], 'integer'],
            [['quantity', 'price', 'sum', 'returnp', 'postponed'], 'number'],
            [['datetime','date','date_start','date_end', 'id_menu', 'id_byuer','name_product','id_type'], 'safe'],
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
        $query = Sell::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		 $query->joinWith(['idMenu']);
		   
         $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
      //  if ( $this->date_start)  $this->date_start=$this->date_start." 00:00:00";
      //  if ($this->date_end)  $this->date_end=$this->date_end." 23:59:59";
       

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_user' => $this->id_user,
            //'id_menu' => $this->id_menu,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'sum' => $this->sum,
			 'product.id' => $this->name_product,
            'sold' => $this->sold,
         //   'datetime' => $this->datetime,
            'sell.id_client' => $this->id_client,
            'number' => $this->number,
            'flag' => $this->flag,
            'returnp' => $this->returnp,
            'id_store' => $this->id_store,
            'postponed' => $this->postponed,
            'id_place' => $this->id_place,
			'id_byuer' => $this->id_byuer,
            'menu.id_type' => $this->id_type,
        ])
		 ->andFilterWhere(['like', 'menu.name', $this->id_menu]);
    $query->andFilterWhere(['between','date',$this->date_start,$this->date_end]);
    $query->orWhere(['IS', 'date', NULL]);
        return $dataProvider;
    }
}
