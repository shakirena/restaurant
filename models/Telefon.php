<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "telefon".
 *
 * @property integer $id
 * @property string $name
 *
 * @property TelefonAr[] $telefonArs
 */
class Telefon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'telefon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelefonArs()
    {
        return $this->hasMany(TelefonAr::className(), ['id_tel' => 'id']);
    }
}
