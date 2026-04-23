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
class Sell extends \yii\db\ActiveRecord
{
    public $id_product;
    public $id_type;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sell';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_menu', 'datetime', 'id_place'], 'required'],
            [['id_user', 'id_menu', 'sold', 'id_client', 'number', 'flag', 'id_store', 'id_place'], 'integer'],
            [['quantity', 'price', 'sum', 'returnp', 'postponed'], 'number'],
            [['datetime','date'], 'safe'],
            [['id_place'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['id_place' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['id_user' => 'id_user']],
            [['id_menu'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['id_menu' => 'id']],
            [['id_client'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['id_client' => 'id_client']],
            [['id_store'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['id_store' => 'id']],
			[['id_byuer'], 'exist', 'skipOnError' => true, 'targetClass' => Byuer::className(), 'targetAttribute' => ['id_byuer' => 'id']],
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
            'id_product' => 'Mallın adı',
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

    public function getProductName()
    {
        return  Product::findOne(["id"=>$this->id_product])->name;
    }
    public function getMenuName()
    {
        return Menu::findOne(["id"=>$this->id_menu])->name;
    }
    public function getGetQuantityConsist()
    { 
       return  round($this->getGetQuantity() * Consist::findOne(["id_menu"=>$this->id_menu,"id_product"=>$this->id_product])->quantity,2);
    }
    public function getGetQuantity()
    { 
      // return  round(Sell::find()->where(["id_product"=>$this->id_product]))->quantity,2);

      return $this->quantity;
    }
    public function getPriceConsist()
    { 
       return  round($this->getGetQuantityConsist() * Arrival::find()->where(["id_product"=>$this->id_product])->orderBy("id DESC")->one()->price,2);
    }

    
    public function getSumConsist($model,$column){
        $sum=0;
        $query =  Sell::find()->select("consist.id_product as id_product,sell.id_menu,datetime, sum(consist.quantity * sell.quantity) as quantity, sum(sell.sum) as sum")
            ->innerJoin("consist","sell.id_menu = consist.id_menu")
			->andWhere($model->where)
			->one();
       /* if ($column === 'price' )   return  round($query->quantity * Arrival::find()->where(["id_product"=>$this->id_product])->orderBy("id DESC")->one()->price,2);
            */
        return $query->$column;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdClient()
    {
        return $this->hasOne(Client::className(), ['id_client' => 'id_client']);
    }
	public function getIdByuer()
    {
        return $this->hasOne(Byuer::className(), ['id' => 'id_byuer']);
    }
	public function getSumValue()
	{
	
		return round($this->quantity*$this->price,2);
	}
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'id_store']);
    }
    public function getGetNumberSell()
    {

if (  Yii::$app->user->identity->id_role==2 || Yii::$app->user->identity->id_role==3 )
        return $this->number."   ".Html::button("Ləğv et",["onclick"=>"returnSellPassword2($this->number)"]).Html::button("Print",["onclick"=>"printChek($this->number)"]); 
else 
	return $this->number."   ".Html::button("Print",["onclick"=>"printChek($this->number)"]); 
		}



    public function getSumArrival($model,$column){
        $sum=0;
        $query =  Sell::find()->select("sum(quantity) as quantity,sum(sum) as sum")
            ->joinWith('idMenu')
            ->joinWith('idClient')
			 ->joinWith('idPlace')
            ->andWhere(['returnp'=>0])
			->andWhere($model->where)
			->one();


        return $query->$column;
    }

	public function getReturnCount()
	{
	
	if ($return = Returnp::find()->select("sum(quantity) as quantity")->where(['id_sell' => $this->number])->one())
		return  "<div style='color:red'>".$return->quantity."</div>";
	else return 0;
	}
}
