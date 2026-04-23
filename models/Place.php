<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "place".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Sell[] $sells
 * @property Sell2[] $sell2s
 */
class Place extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'place';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
			[['id_type'], 'integer'],
            [['name'], 'string', 'max' => 30],
			[['id_type'], 'exist', 'skipOnError' => true, 'targetClass' => TypePlace::className(), 'targetAttribute' => ['id_type' => 'id']],
			
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSells()
    {
        return $this->hasMany(Sell::className(), ['id_place' => 'id']);
    }
	public function getIdType()
    {
        return $this->hasOne(TypePlace::className(), ['id' => 'id_type']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSell2s()
    {
        return $this->hasMany(Sell2::className(), ['id_place' => 'id']);
    }
}
