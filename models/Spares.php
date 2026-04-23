<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "spares".
 *
 * @property integer $id
 * @property string $vid
 * @property string $name
 * @property double $price
 * @property string $bar_code
 * @property integer $id_type
 *
 * @property Arrival[] $arrivals
 * @property Liquiad[] $liquiads
 * @property ReturnAr[] $returnArs
 * @property Sell[] $sells
 * @property TypeSp $idType
 * @property Transfer[] $transfers
 */
class Spares extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spares';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vid', 'name'], 'required'],
            [['vid'], 'string'],
            [['price'], 'number'],
            [['id_type'], 'integer'],
            [['name', 'bar_code'], 'string', 'max' => 50],
            [['id_type'], 'exist', 'skipOnError' => true, 'targetClass' => TypeSp::className(), 'targetAttribute' => ['id_type' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vid' => 'Malın kodu',
            'name' => 'Ad',
            'price' => 'Price',
            'bar_code' => 'Bar Code',
            'id_type' => 'Mal Qrupu',
            'cat'=>'Malin alt qrupu'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArrivals()
    {
        return $this->hasMany(Arrival::className(), ['id_spr' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLiquiads()
    {
        return $this->hasMany(Liquiad::className(), ['id_spr' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReturnArs()
    {
        return $this->hasMany(ReturnAr::className(), ['id_spr' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSells()
    {
        return $this->hasMany(Sell::className(), ['fid_spr' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdType()
    {
        return $this->hasOne(TypeSp::className(), ['id' => 'id_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransfers()
    {
        return $this->hasMany(Transfer::className(), ['id_product' => 'id']);
    }

    public function getCatValue()
    {
       return TypeSp::find()->where(["id" => $this->getIdType()->one()->cat])->one()->name;
    }

}
