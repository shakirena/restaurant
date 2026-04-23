<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "consist".
 *
 * @property integer $id
 * @property integer $id_menu
 * @property integer $id_product
 * @property double $quantity
 *
 * @property Product $idProduct
 * @property Menu $idMenu
 */
class Consist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'consist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_product', 'quantity'], 'required'],
            [['id_menu', 'id_product','id_user'], 'integer'],
            [['quantity'], 'number'],
            [['id_product'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['id_product' => 'id']],
            [['id_menu'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['id_menu' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['id_user' => 'id_user']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_menu' => 'Id Menu',
            'id_product' => 'Id Product',
            'quantity' => 'Quantity',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'id_product']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'id_menu']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUser()
    {
        return $this->hasOne(Users::className(), ['id_user' => 'id_user']);
    }

    public function getPrice()
    {
       return Arrival::find()->where(["id_product" => $this->id_product])->orderBy("id DESC")->one()->price;
    }

    public function getSum()
    {
        return round($this->getPrice() * $this->quantity,2);
    }
}
