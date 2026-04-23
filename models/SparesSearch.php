<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Spares;

/**
 * SparesSearch represents the model behind the search form about `app\models\Spares`.
 */
class SparesSearch extends Spares
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_type'], 'integer'],
            [['vid', 'name', 'bar_code'], 'safe'],
            [['price'], 'number'],
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
        $query = Spares::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'price' => $this->price,
            'id_type' => $this->id_type,
        ]);

        $query->andFilterWhere(['like', 'vid', $this->vid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'bar_code', $this->bar_code]);

        return $dataProvider;
    }
}
