<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "store".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Arrival[] $arrivals
 * @property Client[] $clients
 * @property Client[] $clients0
 * @property Sell[] $sells
 * @property TelefonAr[] $telefonArs
 * @property Transfer[] $transfers
 * @property Transfer[] $transfers0
 */
class Store extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
			[['ip'], 'string', 'max' => 200],
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
    public function getArrivals()
    {
        return $this->hasMany(Arrival::className(), ['id_store' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClients()
    {
        return $this->hasMany(Client::className(), ['id_filial' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClients0()
    {
        return $this->hasMany(Client::className(), ['g_filial' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSells()
    {
        return $this->hasMany(Sell::className(), ['id_filial' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelefonArs()
    {
        return $this->hasMany(TelefonAr::className(), ['id_filial' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransfers()
    {
        return $this->hasMany(Transfer::className(), ['otkuda' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransfers0()
    {
        return $this->hasMany(Transfer::className(), ['kuda' => 'id']);
    }
}
