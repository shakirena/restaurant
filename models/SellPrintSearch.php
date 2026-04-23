<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sell;

/**
 * SellSearch represents the model behind the search form about `app\models\Sell`.
 */
class SellPrintSearch extends Sell2
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_user', 'id_menu', 'sold', 'id_client', 'number', 'flag', 'id_store', 'id_place'], 'integer'],
            [['quantity', 'price', 'sum', 'returnp', 'postponed'], 'number'],
            [['datetime'], 'safe'],
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
        $query = Sell2::find()->where(['sold' => 0])->andWhere("print!=quantity");

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			 'pagination' => [

                'pageSize' =>'200',
            ],
        ]);

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
            'id_menu' => $this->id_menu,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'sum' => $this->sum,
            'sold' => $this->sold,
            'datetime' => $this->datetime,
            'id_client' => $this->id_client,
            'number' => $this->number,
            'flag' => $this->flag,
            'returnp' => $this->returnp,
            'id_store' => $this->id_store,
            'postponed' => $this->postponed,
            'id_place' => $this->id_place,
        ]);
       
        return $dataProvider;
    }
}
