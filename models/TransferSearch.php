<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transfer;

/**
 * TransferSearch represents the model behind the search form about `app\models\Transfer`.
 */
class TransferSearch extends Transfer
{
    /**
     * @inheritdoc
     */
    public $date_start;
    public $date_end;
    public function rules()
    {

        return [
            [['id', 'id_user', 'otkuda', 'kuda', 'received', 'number'], 'integer'],
            [['datetime', 'date_start','date_end', 'id_product'], 'safe'],
            [['quantity'], 'number'],
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
        $query = Transfer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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


            'quantity' => $this->quantity,
            'otkuda' => $this->otkuda,
            'kuda' => $this->kuda,
            'received' => $this->received,
            'number' => $this->number,
        ])
            ->andFilterWhere(['like', 'spares.name',$this->id_product]);

        $query->andFilterWhere(['between','datetime',$this->date_start,$this->date_end]);

        return $dataProvider;
    }
}
