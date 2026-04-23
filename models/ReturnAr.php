<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "return_ar".
 *
 * @property integer $id
 * @property integer $id_spr
 * @property double $quantity
 * @property integer $id_contr
 * @property string $date
 *
 * @property Spares $idSpr
 * @property Contractor $idContr
 */
class ReturnAr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'return_ar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_spr', 'quantity', 'id_contr', 'date'], 'required'],
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
            'quantity' => 'Quantity',
            'id_contr' => 'Id Contr',
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
