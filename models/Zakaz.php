<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zakaz".
 *
 * @property integer $id
 * @property integer $id_spr
 * @property string $name
 * @property double $quantity
 */
class Zakaz extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zakaz';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_spr'], 'integer'],
            [['quantity'], 'required'],
            [['quantity'], 'number'],
            [['name'], 'string', 'max' => 200],
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
            'name' => 'Name',
            'quantity' => 'Quantity',
        ];
    }
}
