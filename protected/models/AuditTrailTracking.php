<?php

/**
 * This is the model class for table "tbl_audit_trail_ii".
 *
 * The followings are the available columns in table 'tbl_audit_trail_ii':
 * @property integer $id
 * @property string $message_type
 * @property string $short_descrip
 * @property string $long_descrip
 * @property string $controller_name
 * @property string $user_id
 * @property string $user_group
 * @property integer $category_id
 * @property string $model_name
 * @property integer $priority
 * @property string $category
 * @property string $timestamp
 */
class AuditTrailTracking extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AuditTrailTracking the static model class
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
		return 'tbl_audit_trail_ii';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('message_type, short_descrip, long_descrip, controller_name, user_id, user_group, category_id, model_name, priority, category, timestamp', 'required'),
			array('category_id, priority', 'numerical', 'integerOnly'=>true),
			array('message_type', 'length', 'max'=>10),
			array('short_descrip', 'length', 'max'=>255),
			array('controller_name, user_group, model_name, category', 'length', 'max'=>50),
			array('user_id', 'length', 'max'=>75),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, message_type, short_descrip, long_descrip, controller_name, user_id, user_group, category_id, model_name, priority, category, timestamp', 'safe', 'on'=>'search'),
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
			'message_type' => 'Message Type',
			'short_descrip' => 'Short Descrip',
			'long_descrip' => 'Long Descrip',
			'controller_name' => 'Controller Name',
			'user_id' => 'User',
			'user_group' => 'User Group',
			'category_id' => 'Category',
			'model_name' => 'Model Name',
			'priority' => 'Priority',
			'category' => 'Category',
			'timestamp' => 'Timestamp',
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
		$criteria->compare('message_type',$this->message_type,true);
		$criteria->compare('short_descrip',$this->short_descrip,true);
		$criteria->compare('long_descrip',$this->long_descrip,true);
		$criteria->compare('controller_name',$this->controller_name,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('user_group',$this->user_group,true);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('model_name',$this->model_name,true);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}