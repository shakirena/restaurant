<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "finance".
 *
 * @property integer $id
 * @property integer $id_client
 * @property double $sum
 * @property integer $id_contr
 * @property string $specification
 * @property integer $sign
 * @property string $data
 * @property integer $id_user
 * @property integer $flag
 * @property integer $number
 *
 * @property ClientFio $idClient
 * @property Contractor $idContr
 * @property Users $idUser
 */
class Finance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'finance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_client', 'id_contr', 'sign', 'id_user', 'flag', 'number'], 'integer'],
            [['sum', 'sign', 'data'], 'required'],
            [['sum'], 'number'],
            [['data'], 'safe'],
            [['specification'], 'string', 'max' => 255],
            [['id_client'], 'exist', 'skipOnError' => true, 'targetClass' => ClientFio::className(), 'targetAttribute' => ['id_client' => 'id']],
            [['id_contr'], 'exist', 'skipOnError' => true, 'targetClass' => Contractor::className(), 'targetAttribute' => ['id_contr' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_client' => 'Id Client',
            'sum' => 'Sum',
            'id_contr' => 'Id Contr',
            'specification' => 'Specification',
            'sign' => 'Sign',
            'data' => 'Data',
            'id_user' => 'Id User',
            'flag' => 'Flag',
            'number' => 'Number',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdClient()
    {
        return $this->hasOne(ClientFio::className(), ['id' => 'id_client']);
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
    public function getIdUser()
    {
        return $this->hasOne(Users::className(), ['id_user' => 'id_user']);
    }
}
