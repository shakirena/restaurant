<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "telefon_ar".
 *
 * @property integer $id
 * @property double $quantity
 * @property double $price
 * @property double $sum
 * @property string $date
 * @property integer $id_tel
 * @property integer $received
 * @property integer $id_user
 * @property integer $id_filial
 * @property double $rest
 * @property integer $number
 *
 * @property Users $idUser
 * @property Telefon $idTel
 * @property Store $idFilial
 */
class TelefonAr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'telefon_ar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quantity', 'price', 'sum', 'id_tel', 'id_user'], 'required'],
            [['quantity', 'price', 'sum', 'rest'], 'number'],
            [['date'], 'safe'],
            [['id_tel', 'received', 'id_user', 'id_filial', 'number'], 'integer'],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['id_user' => 'id']],
            [['id_tel'], 'exist', 'skipOnError' => true, 'targetClass' => Telefon::className(), 'targetAttribute' => ['id_tel' => 'id']],
            [['id_filial'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['id_filial' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'sum' => 'Sum',
            'date' => 'Date',
            'id_tel' => 'Id Tel',
            'received' => 'Received',
            'id_user' => 'Id User',
            'id_filial' => 'Id Filial',
            'rest' => 'Rest',
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
    public function getIdTel()
    {
        return $this->hasOne(Telefon::className(), ['id' => 'id_tel']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdFilial()
    {
        return $this->hasOne(Store::className(), ['id' => 'id_filial']);
    }
}
