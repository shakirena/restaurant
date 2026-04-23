<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kassa".
 *
 * @property integer $id
 * @property string $name
 * @property double $sum
 */
class Kassa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kassa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['sum','pos'], 'number'],
            [['name'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'sum' => 'Sum',
        ];
    }
}
