<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property integer $id_type
 * @property integer $barcode
 * @property string $name
 * @property string $note
 * @property double $price
 * @property string $vid
 *
 * @property Type $idType
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
      * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_type',  'name', 'price', 'vid'], 'required'],
            [['id_type', 'barcode', 'disabled'], 'integer'],
            [['price','price_vip'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['note'], 'string', 'max' => 50],
            [['vid'], 'string', 'max' => 5],
            [['id_type'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['id_type' => 'id']],
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
            'id_type' => 'Yəmək gruppu',
            'barcode' => 'Barcode',  
			'disabled' => 'Deaktiv',
            'name' => 'Yəmək adı',
            'note' => 'Note',
            'price' => 'Qiyməti',
			'price_vip' => 'Qiyməti VIP',
            'vid' => 'Vid',
			
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdType()
    {
        return $this->hasOne(Type::className(), ['id' => 'id_type']);
    }
	public function getIdProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'id_product']);
    }
	
	public function getSklad1()
	{
	if ($this->sklad==0) return  "<input onChange='changeSklad($this->id) ' type='checkbox' name=$this->id >";
	else return "<input type='checkbox'  onChange='changeSklad($this->id)' name=$this->id checked>";
	
	}
	
	 public function getIdStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'id_store']);
    }
	
	
	public function getDisabledVal()
	{
		if ($this->disabled==0) return  "<input onChange='changeDisabled($this->id) ' type='checkbox' name=$this->id >";
		else return "<input type='checkbox'  onChange='changeDisabled($this->id)' name=$this->id checked>";
	
		
	}	
  
}
