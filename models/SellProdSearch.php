<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sell;

/**
 * SellSearch represents the model behind the search form about `app\models\Sell`.
 */
class SellProdSearch extends Sell
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
            [['datetime','date', 'date_start','date_end', 'id_menu','name_product','id_type'], 'safe'],
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
        $query = Sell::find()->select("id_menu,datetime,sum(sum) as sum,sum(quantity) as quantity,date");
        
        //->groupBy("id_menu");

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
      
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_user' => $this->id_user,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'sum' => $this->sum,
            'sold' => $this->sold,
            'id_client' => $this->id_client,
            'number' => $this->number,
            'flag' => $this->flag,
            'returnp' => $this->returnp,
            'id_store' => $this->id_store,
            'postponed' => $this->postponed,
            'id_place' => $this->id_place,   
            'menu.id_type' => $this->id_type,
        ])
		 ->andFilterWhere(['like', 'menu.name', $this->id_menu]);
         $query->andFilterWhere(['between','date',$this->date_start,$this->date_end]);
         $query->orWhere(['IS', 'date', NULL]);
        $query->groupBy("id_menu");
        return $dataProvider;
    }
}
