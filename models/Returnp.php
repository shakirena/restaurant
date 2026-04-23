<?php

namespace app\models;
use yii\helpers\Html;
use Yii;

/**
 * This is the model class for table "returnp".
 *
 * @property integer $id
 * @property integer $id_product
 * @property integer $id_user
 * @property integer $id_client
 * @property string $reason
 * @property string $data
 * @property double $quantity
 *
 * @property Client $idClient
 * @property Users $idUser
 * @property Product $idProduct
 */
class Returnp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'returnp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_product', 'id_user', 'number'], 'required'],
            [['id_product', 'id_user','number','received','id_sell'], 'integer'],
            [['data'], 'safe'],
            [['quantity'], 'number'],
            [['reason'], 'string', 'max' => 20],
      
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['id_user' => 'id_user']],
            [['id_product'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['id_product' => 'id']],
           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_product' => 'Adı',
            'id_user' => 'Satıcı',
            'reason' => 'Reason',
            'data' => 'Tarix',
            'quantity' => 'Sayı',
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
        return $this->hasOne(Menu::className(), ['id' => 'id_product']);
    }

    public function getSumNumber($number){
            $model=Returnp::find()->select("sum(quantity) as quantity")->where(["number"=>$number])->one()->quantity;

        return $model;
    }

    public function getSumReturn($model){
        $sum=0;
        $query =  Returnp::find()->select("sum(quantity) as quantity")
            ->joinWith('idProduct.idType')
            ->where($model->where)->one();


        return $query->quantity;
    }


    public function getGetNumber()
    {


        return $this->number."   ".Html::button('<i class="glyphicon glyphicon-ok"></i>Təsdiqlə',["onclick"=> "receivedReturn($this->number)"]);
    }

}
