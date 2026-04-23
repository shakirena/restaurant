<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "users".
 *
 * @property integer $id_user
 * @property string $name
 * @property integer $id_role
 * @property string $login
 * @property string $password
 *
 * @property Arrival[] $arrivals
 * @property Cdebt[] $cdebts
 * @property Roles $idRole
 */
class Users extends  \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fio', 'id_role', 'login', 'password'], 'required'],
            [['id_role', 'disabled'], 'integer'],
            [['fio'], 'string', 'max' => 20],
            [['login'], 'string', 'max' => 10],
            [['password'], 'string', 'max' => 100],
            [['id_role'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::className(), 'targetAttribute' => ['id_role' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'fio' => 'Name',
            'id_role' => 'Id Role',
            'login' => 'Login',
            'password' => 'Password',
			'disabled' => 'Deaktiv',
        ];
    }
    public static function findByUsername($username)
    {
        return static::findOne([
            'login' => $username
        ]);
    }
    public function setPassword($password) {
        $this->password=Yii::$app->security->generatePasswordHash($password);
    }
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
    /* Аутентификация пользователей */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }



    public function getId()
    {
        return $this->id_user;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    public function getAuthKey()
    {

    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArrivals()
    {
        return $this->hasMany(Arrival::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCdebts()
    {
        return $this->hasMany(Cdebt::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdRole()
    {
        return $this->hasOne(Roles::className(), ['id' => 'id_role']);
    }
	
		public function getDisabledVal()
	{
		if ($this->disabled==0) return  "<input onChange='changeDisabled($this->id) ' type='checkbox' name=$this->id >";
		else return "<input type='checkbox'  onChange='changeDisabled($this->id)' name=$this->id checked>";
	
		
	}	
  
}
