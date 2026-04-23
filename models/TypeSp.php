<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "type_sp".
 *
 * @property integer $id
 * @property string $name
 * @property string $vid
 * @property integer $cat
 *
 * @property Spares[] $spares
 */
class TypeSp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'type_sp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['vid'], 'string'],
            [['cat'], 'integer'],
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
            'name' => 'Mal Qrupu',
            'vid' => 'Kod',
            'cat' => 'Grupun adı',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpares()
    {
        return $this->hasMany(Spares::className(), ['id_type' => 'id']);
    }
}
