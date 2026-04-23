<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "balance".
 *
 * @property integer $id
 * @property integer $id_user
 * @property string $datetime
 * @property double $sum
 * @property double $current_sum
 * @property integer $id_type
 * @property string $note
 * @property integer $number
 * @property string $user_name
 * @property integer $id_client
 * @property integer $id_store
 *
 * @property Store $idStore
 * @property Users $idUser
 * @property TypeBalance $idType
 * @property ClientFio $idClient
 */
class Balance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'balance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'datetime', 'sum',  'id_type'], 'required'],
            [['id_user', 'id_type', 'number', 'id_client', 'id_store'], 'integer'],
            [['datetime'], 'safe'],
            [['sum'], 'number'],
            [['note'], 'string', 'max' => 50],
            [['user_name'], 'string', 'max' => 100],
            [['id_store'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['id_store' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['id_user' => 'id_user']],
            [['id_type'], 'exist', 'skipOnError' => true, 'targetClass' => TypeBalance::className(), 'targetAttribute' => ['id_type' => 'id']],
            [['id_client'], 'exist', 'skipOnError' => true, 'targetClass' => ClientFio::className(), 'targetAttribute' => ['id_client' => 'id']],
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
            'datetime' => 'Datetime',
            'sum' => 'Sum',
            'id_type' => 'Id Type',
            'note' => 'Note',
            'number' => 'Number',
            'user_name' => 'User Name',
            'id_client' => 'Id Client',
            'id_store' => 'Id Store',
        ];
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
    public function getIdUser()
    {
        return $this->hasOne(Users::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdType()
    {
        return $this->hasOne(TypeBalance::className(), ['id' => 'id_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdClient()
    {
        return $this->hasOne(ClientFio::className(), ['id' => 'id_client']);
    }
}
