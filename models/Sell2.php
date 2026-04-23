<?php

namespace app\models;

use Yii;
use yii\bootstrap\Html;
/**
 * This is the model class for table "sell".
 *
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_menu
 * @property double $quantity
 * @property double $price
 * @property double $sum
 * @property integer $sold
 * @property string $datetime
 * @property integer $id_client
 * @property integer $number
 * @property integer $flag
 * @property double $returnp
 * @property integer $id_store
 * @property double $postponed
 * @property integer $id_place
 *
 * @property Place $idPlace
 * @property Users $idUser
 * @property Menu $idMenu
 * @property Client $idClient
 * @property Store $idStore
 */
class Sell2 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sell2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_menu', 'datetime'], 'required'],
            [['id_user', 'id_menu', 'sold', 'id_client', 'number', 'flag', 'id_store', 'id_place','discount'], 'integer'],
            [['quantity', 'price', 'sum', 'returnp', 'postponed','print'], 'number'],
            [['datetime'], 'safe'],
            [['id_place'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['id_place' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['id_user' => 'id_user']],
            [['id_menu'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['id_menu' => 'id']],
            [['id_client'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['id_client' => 'id_client']],
            [['id_store'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['id_store' => 'id']],
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
            'id_menu' => 'Id Menu',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'sum' => 'Sum',
            'sold' => 'Sold',
            'datetime' => 'Datetime',
            'id_client' => 'Id Client',
            'number' => 'Number',
            'flag' => 'Flag',
            'returnp' => 'Returnp',
            'id_store' => 'Id Store',
            'postponed' => 'Postponed',
            'id_place' => 'Id Place',
			'discount' => 'Güzəşt'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPlace()
    {
        return $this->hasOne(Place::className(), ['id' => 'id_place']);
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
    public function getIdMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'id_menu']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdClient()
    {
        return $this->hasOne(Client::className(), ['id_client' => 'id_client']);
    }
  public function getQuantityPrint()
    {
        $quantity=$this->quantity-$this->print;
        return $quantity;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'id_store']);
    }
	
	public function getSumValue()
	{
	
		return round($this->quantity*$this->price,2);
	}
    public function getGetNumber()
    {


        return $this->number."   ".Html::button('<i class="glyphicon glyphicon-ok"></i>OK',["onclick"=> "cancel($this->number)"]);
    }
}
