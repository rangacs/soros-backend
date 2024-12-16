<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $access_token
 * @property string $password_hash
 * @property string $confirmation_token
 * @property integer $status
 * @property integer $superadmin
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $registration_ip
 * @property string $bind_to_ip
 * @property string $email
 * @property integer $email_confirmed
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, created_at, updated_at', 'required'),
			array('status, superadmin, created_at, updated_at, email_confirmed', 'numerical', 'integerOnly'=>true),
			array('username, password_hash, confirmation_token, bind_to_ip', 'length', 'max'=>255),
			array('auth_key, access_token', 'length', 'max'=>32),
			array('registration_ip', 'length', 'max'=>15),
			array('email', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, auth_key, access_token, password_hash, confirmation_token, status, superadmin, created_at, updated_at, registration_ip, bind_to_ip, email, email_confirmed', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'auth_key' => 'Auth Key',
			'access_token' => 'Access Token',
			'password_hash' => 'Password Hash',
			'confirmation_token' => 'Confirmation Token',
			'status' => 'Status',
			'superadmin' => 'Superadmin',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'registration_ip' => 'Registration Ip',
			'bind_to_ip' => 'Bind To Ip',
			'email' => 'Email',
			'email_confirmed' => 'Email Confirmed',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('auth_key',$this->auth_key,true);
		$criteria->compare('access_token',$this->access_token,true);
		$criteria->compare('password_hash',$this->password_hash,true);
		$criteria->compare('confirmation_token',$this->confirmation_token,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('superadmin',$this->superadmin);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('updated_at',$this->updated_at);
		$criteria->compare('registration_ip',$this->registration_ip,true);
		$criteria->compare('bind_to_ip',$this->bind_to_ip,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('email_confirmed',$this->email_confirmed);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function validatePassword($submittedPassword){
		
		$salt = 'sabia-helios'; //salt
		
		$tmpPass = $submittedPassword.$salt;
		
		$uHash = md5($submittedPassword.$salt);
		if($this->password_hash == $uHash){
			
			return true;
		}else{
			
			return false;
		}
		
		
	}
}
