<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "liquiad".
 *
 * @property integer $id
 * @property integer $id_spr
 * @property integer $id_contr
 * @property double $quantity
 * @property string $date
 *
 * @property Spares $idSpr
 * @property Contractor $idContr
 */
class Liquiad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'liquiad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_spr', 'id_contr', 'quantity', 'date'], 'required'],
            [['id_spr', 'id_contr'], 'integer'],
            [['quantity'], 'number'],
            [['date'], 'safe'],
            [['id_spr'], 'exist', 'skipOnError' => true, 'targetClass' => Spares::className(), 'targetAttribute' => ['id_spr' => 'id']],
            [['id_contr'], 'exist', 'skipOnError' => true, 'targetClass' => Contractor::className(), 'targetAttribute' => ['id_contr' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_spr' => 'Id Spr',
            'id_contr' => 'Id Contr',
            'quantity' => 'Quantity',
            'date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSpr()
    {
        return $this->hasOne(Spares::className(), ['id' => 'id_spr']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdContr()
    {
        return $this->hasOne(Contractor::className(), ['id' => 'id_contr']);
    }
}
