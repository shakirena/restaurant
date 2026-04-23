<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contractor".
 *
 * @property integer $id
 * @property string $name
 * @property string $telephone
 *
 * @property Debt[] $debts
 * @property Finance[] $finances
 * @property Liquiad[] $liquiads
 * @property ReturnAr[] $returnArs
 */
class Contractor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contractor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'telephone'], 'string', 'max' => 100],
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
            'telephone' => 'Telephone',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDebts()
    {
        return $this->hasMany(Debt::className(), ['id_contr' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinances()
    {
        return $this->hasMany(Finance::className(), ['id_contr' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLiquiads()
    {
        return $this->hasMany(Liquiad::className(), ['id_contr' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReturnArs()
    {
        return $this->hasMany(ReturnAr::className(), ['id_contr' => 'id']);
    }
}
