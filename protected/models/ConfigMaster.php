<?php

/**
 * This is the model class for table "rta_config_master".
 *
 * The followings are the available columns in table 'rta_config_master':
 * @property string $rtaMasterID
 * @property string $DB_ID_string
 * @property string $rtaConfigTable
 * @property string $DBwriteActive
 * @property string $data_type
 * @property string $accessLevel
 * @property integer $write_frequency
 * @property integer $DB_counter
 * @property double $timeConversionMassflowWeight
 * @property string $UserComments
 */
class ConfigMaster extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rta_config_master';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('DB_ID_string, rtaConfigTable', 'required'),
			array('write_frequency, DB_counter', 'numerical', 'integerOnly'=>true),
			array('timeConversionMassflowWeight', 'numerical'),
			array('DB_ID_string', 'length', 'max'=>30),
			array('rtaConfigTable', 'length', 'max'=>19),
			array('DBwriteActive, accessLevel', 'length', 'max'=>1),
			array('data_type', 'length', 'max'=>16),
			array('UserComments', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rtaMasterID, DB_ID_string, rtaConfigTable, DBwriteActive, data_type, accessLevel, write_frequency, DB_counter, timeConversionMassflowWeight, UserComments', 'safe', 'on'=>'search'),
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
			'rtaMasterID' => 'Rta Master',
			'DB_ID_string' => 'Db Id String',
			'rtaConfigTable' => 'Rta Config Table',
			'DBwriteActive' => 'Dbwrite Active',
			'data_type' => 'Data Type',
			'accessLevel' => 'Access Level',
			'write_frequency' => 'Write Frequency',
			'DB_counter' => 'Db Counter',
			'timeConversionMassflowWeight' => 'Time Conversion Massflow Weight',
			'UserComments' => 'User Comments',
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

		$criteria->compare('rtaMasterID',$this->rtaMasterID,true);
		$criteria->compare('DB_ID_string',$this->DB_ID_string,true);
		$criteria->compare('rtaConfigTable',$this->rtaConfigTable,true);
		$criteria->compare('DBwriteActive',$this->DBwriteActive,true);
		$criteria->compare('data_type',$this->data_type,true);
		$criteria->compare('accessLevel',$this->accessLevel,true);
		$criteria->compare('write_frequency',$this->write_frequency);
		$criteria->compare('DB_counter',$this->DB_counter);
		$criteria->compare('timeConversionMassflowWeight',$this->timeConversionMassflowWeight);
		$criteria->compare('UserComments',$this->UserComments,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ConfigMaster the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
