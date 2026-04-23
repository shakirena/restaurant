<?php

namespace app\models;

use Yii;
use yii\bootstrap\Html;
/**
 * This is the model class for table "arrival".
 *
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_product
 * @property double $quantity
 * @property double $price
 * @property double $usd
 * @property double $pricesell
 * @property double $sum
 * @property integer $id_store
 * @property integer $received
 * @property string $datetime
 * @property integer $id_contr
 * @property integer $number
 * @property integer $discount
 * @property integer $returnp
 * @property integer $rest
 *
 * @property Users $idUser
 * @property Store $idStore
 * @property Contractor $idContr
 * @property Product $idProduct
 */
class Arrival extends \yii\db\ActiveRecord
{
    public $sumsell;
    public $flag;
    public $sum_azn;
    public $sum_usd;
    public $name_product;
    public $DIF=0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'arrival';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_product'], 'required'],
            [['id_user', 'id_product', 'id_store', 'received', 'id_contr', 'number', 'returnp','postponed'], 'integer'],
            [['quantity', 'price', 'usd', 'pricesell','price_top','pack', 'sum', 'rest', 'discount'], 'number'],
            [['datetime'], 'safe'],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['id_user' => 'id_user']],
            [['id_store'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['id_store' => 'id']],
            [['id_contr'], 'exist', 'skipOnError' => true, 'targetClass' => Contractor::className(), 'targetAttribute' => ['id_contr' => 'id']],
            [['id_product'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['id_product' => 'id']],
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
            'id_product' => 'Malın adı',
            'quantity' => 'Quantity',
            'price' => 'Alış qiyməti(azn)',
            'price_top' => 'Ədədin <br>qyiməti',
		
            'pack' => 'Qutuda <br>ədəd sayi',
            'usd' => 'Aləş qiyməti(usd)',
            'pricesell' => 'Satış qiyməti',
            'sum' => "Cəmi <br>qiyməti",
            'id_store' => 'Id Store',
            'received' => 'Received',
            'datetime' => 'Datetime',
            'id_contr' => 'Şirkət',
            'number' => 'Number',
            'discount' => 'Discount',
            'returnp' => 'Returnp',
            'rest' => 'Say',
            'type' =>'Type',
            'type_name' =>'Type',
             'name_product'=> 'Malın adı',
		
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
    public function getIdStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'id_store']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdContr()
    {
        return $this->hasOne(Contractor::className(), ['id' => 'id_contr']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'id_product']);
    }
    public function getProduct()
    {
        return   Menu::findOne(['id'=>$this->id_product]);
    }



    public function getGetPostponed()
    {

        $sell=Sell::find()->select("sum(postponed) as postponed")->where(["id_product" => $this->id_product,'returnp'=>0])
            ->groupBy("id_product")->one();
        return $sell->postponed;
    }

  
    public function getPriceSum(){
        $model=Arrival::find()->where(["id_product"=>$this->id_product])->orderBy("id DESC")->one();
      
		return round($model->price*$this->rest,2);
	
    }
    public function getSumRest($model,$column){
        $sum=0;
        $query = Arrival::find()->select('sum(rest) as rest,sum(price*rest) as sum,sum(usd*rest) as usd,arrival.id_product,id_store,sum(pricesell*rest) as pricesell')
            ->where($model->where)
            ->orderBy("arrival.id DESC")->one();
        return round($query->$column,2);
    }


    public function getSumNumber($number,$column){
        if ($column=="sum_usd")   $model=Arrival::find()->select("sum(usd*quantity) as sum")->where(["number"=>$number])->one()->sum;
        else
       $model=Arrival::find()->select("sum(".$column.") as ".$column)->where(["number"=>$number])->one()->$column;

        return $model;
    }
    public function getPriceRest(){
        $model=Arrival::find()->where(["id_product"=>$this->id_product])->orderBy("id DESC")->one();
        return $model->price;
           
    }

	

	
	public function getQuanSum()
	{
	 $arrival=Menu::find()
            ->select('price')
            ->where(['id' => $this->id_product])
            ->orderBy([
                'id' => SORT_DESC
            ])
            ->one();
	return round($this->quantity*$arrival->price,2);
	}
	
	
	  public function getSumArrival($model,$column){
        $sum=0;
        $query =  Arrival::find()->select("sum(quantity*arrival.price) as sum,sum(quantity) as quantity")
            ->joinWith('idProduct') 
            ->where($model->where)->one();


        return round($query->$column,2);
    }
	 public function getSumPrice($model,$column){
        $sum=0;
        $query =  Arrival::find()->select("sum(rest*arrival.price) as sum")
            ->joinWith('idProduct') 
            ->where($model->where)->one();


        return $query->$column;
    }
    
    public function getPriceSell()
    {
        $arrival=Menu::find()
            ->select('price')
            ->where(['id' => $this->id_product])
            ->orderBy([
                'id' => SORT_DESC
            ])
            ->one();
        if ($arrival->price) $pricesell=$arrival->price;
        else $pricesell=0;
        return $pricesell;
    }
	public function getPriceSellSum()
    {
	$sum = 0;
	 foreach (Arrival::find()->select('sum(rest) as rest,arrival.id_product')
            ->joinWith('idProduct') 
            ->where($model->where) ->groupBy("id_product")->all() as $model) {
				$arrival=Menu::find()
					->select('price')
					->where(['id' => $model->id_product])
					->orderBy([
						'id' => SORT_DESC
					])
					->one();
				$sum = $sum + $model->rest * $arrival->price;
				}
        return $sum;
    }
	public function getPriceSellCemi()
    {
	$sum = 0;
	$price = $this->getPriceSell();
        return $this->rest * $price;
    }
  
    public function getGetNumber()
    {

		$arrival=Arrival::find()
            ->select('sum(quantity) as quantity,sum(rest) as rest')
            ->where(['number' => $this->number])
            ->orderBy([
                'id' => SORT_DESC
            ])
            ->one();
			if ($arrival->quantity==$arrival->rest &&  Yii::$app->user->identity->id_role==2)
        return $this->number."   ".Html::button('<i class="glyphicon glyphicon-remove"></i>',["onclick"=> "cancelArrrival($this->number)",'class' => 'btn btn-primary btn-xs']);
	else return $this->number;
    }

    public function getNameBarcode()
    {
        $bar='';
        foreach (Barcodep::find()->where(["id_product" =>$this->id_product])->all() as $barcode)
        {
            $bar.=$barcode->name.",";
        }
        $bar1=rtrim($bar,",");
        return $bar1;

    }
}
