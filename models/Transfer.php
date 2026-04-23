<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transfer".
 *
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_product
 * @property string $datetime
 * @property double $quantity
 * @property integer $otkuda
 * @property integer $kuda
 * @property integer $received
 * @property integer $number
 *
 * @property Users $idUser
 * @property Spares $idProduct
 * @property Store $otkuda0
 * @property Store $kuda0
 */
class Transfer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transfer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_product', 'quantity'], 'required'],
            [['id_user', 'id_product', 'otkuda', 'kuda', 'received', 'number'], 'integer'],
            [['datetime'], 'safe'],
            [['quantity'], 'number'],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['id_user' => 'id_user']],
            [['id_product'], 'exist', 'skipOnError' => true, 'targetClass' => Spares::className(), 'targetAttribute' => ['id_product' => 'id']],
            [['otkuda'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['otkuda' => 'id']],
            [['kuda'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['kuda' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'id_product' => 'Id Product',
            'datetime' => 'Datetime',
            'quantity' => 'Quantity',
            'otkuda' => 'Otkuda',
            'kuda' => 'Kuda',
            'received' => 'Received',
            'number' => 'Number',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUser()
    {
        return $this->hasOne(Users::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProduct()
    {
        return $this->hasOne(Spares::className(), ['id' => 'id_product']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOtkuda0()
    {
        return $this->hasOne(Store::className(), ['id' => 'otkuda']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKuda0()
    {
        return $this->hasOne(Store::className(), ['id' => 'kuda']);
    }
}
