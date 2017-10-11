<?php

namespace app\models;

use yii\db\Expression;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property resource $password
 * @property string $hash
 * @property integer $type
 * @property integer $status
 * @property string $date_creation
 * @property string $date_modification
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface {
	const TYPE_ADMIN = 3;
	const TYPE_MODERATOR = 2;
	const TYPE_USER = 1;

	const STATUS_INACTIVE = null;
	const STATUS_ACTIVE = 1;

	const SUPER_ADMIN_ID = 1;

	public $newPassword, $confirmPassword;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'email', 'password'], 'required'],
	        [['name'], 'string', 'max' => 32],
	        [['email'], 'email'],
	        [['email'], 'unique'],
	        [['email', 'hash'], 'string', 'max' => 64],
            //[['type', 'status'], 'integer'],
	        //[['password', 'type'], 'unsafe'],
	        [['newPassword', 'confirmPassword'], 'required', 'on'=>'register'],
	        [['newPassword', 'confirmPassword'], 'safe'],
	        [['confirmPassword'], 'compare', 'compareAttribute'=>'newPassword', 'skipOnError'=>true],
	        ['date_creation', 'default', 'value'=>new Expression('NOW()'), 'when'=>function($model) { return $model->isNewRecord; }],
	        ['date_modification', 'default', 'value'=>new Expression('NOW()'), 'when'=>function($model) { return !$model->isNewRecord; }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '#',
	        'name' => 'Имя',
            'email' => 'E-mail',
	        'newPassword' => 'Пароль',
	        'confirmPassword' => 'Подтверждение',
            'hash' => 'Хеш-код',
	        'type' => 'Тип',
	        'status' => 'Статус',
            'date_creation' => 'Дата добавления',
            'date_modification' => 'Дата изменения'
        ];
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getNews() {
		return $this->hasMany(News::className(), ['user_id' => 'id']);
	}

	public function getTypes() {
    	return [
    		self::TYPE_USER => 'Пользователь',
		    self::TYPE_MODERATOR => 'Модератор',
		    self::TYPE_ADMIN => 'Администратор'
	    ];
    }

    public function getStatuses() {
    	return [
    		self::STATUS_INACTIVE => 'Не активен',
		    self::STATUS_ACTIVE => 'Активен'
	    ];
    }

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return static|null
	 */
	public static function findByUsername($username) {
		return static::findOne(['email'=>$username, 'status'=>self::STATUS_ACTIVE]);
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentity($id) {
		return static::findOne(['id'=>$id, 'status'=>self::STATUS_ACTIVE]);
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null) {
		return static::findOne(['hash'=>$token]);
	}

	/**
	 * @inheritdoc
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey() {
		return $this->hash;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey) {
		return $this->hash === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password) {
		$bcrypt = new \app\components\Bcrypt();

		return $bcrypt->verify($password, $this->password);
	}

}
