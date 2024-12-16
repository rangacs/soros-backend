<?php

/**
 * This is the model class for table "profile".
 *
 * The followings are the available columns in table 'profile':
 * @property integer $id
 * @property string $ug_id
 * @property string $hobbies
 * @property string $avatar
 */
class Profile extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Profile the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ug_id', 'length', 'max'=>20),
			array('hobbies', 'length', 'max'=>120),
			array('hobbies', 'required', 'on' => array('updateProfile', 'registration')),
			 array('avatar', 'file', 'types'=>'jpg, gif, png', 'allowEmpty' => true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, ug_id, hobbies', 'safe', 'on'=>'search'),
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
			'ug_id' => 'Ug',
			'hobbies' => 'Hobbies',
			'avatar' => 'Avatar',
		);
	}

	/**
	 * returns an array that contains the views name to be loaded
	 * @return array
	 */
	public function profileViews()
	{
		return array(
			UserGroupsUser::VIEW => 'index',
			UserGroupsUser::EDIT => 'update',
			UserGroupsUser::REGISTRATION => 'register'
		);
	}


	/**
	 * returns an array that contains the name of the attributes that will
	 * be stored in session
	 * @return array
	 */
	public function profileSessionData()
	{
		return array(
			'hobbies',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('ug_id',$this->ug_id,true);
		$criteria->compare('hobbies',$this->hobbies,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}