<?php

/**
 * This is the model class for table "rta_db_config".
 *
 * The followings are the available columns in table 'rta_db_config':
 * @property integer $rta_DB_ID
 * @property string $DB_ID_string
 * @property string $Material_ID
 * @property string $DBwriteActive
 * @property string $analysis_timespan
 * @property integer $averaging_subinterval_secs
 * @property string $write_frequency
 * @property string $detector_ID
 * @property string $date_created
 * @property string $user_created
 * @property integer $DB_counter
 * @property string $User_Comments
 */
class RtaDbConfig extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rta_db_config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('DB_ID_string, date_created, user_created', 'required'),
			array('averaging_subinterval_secs, DB_counter', 'numerical', 'integerOnly'=>true),
			array('DB_ID_string, Material_ID', 'length', 'max'=>16),
			array('DBwriteActive, user_created', 'length', 'max'=>1),
			array('analysis_timespan, write_frequency, detector_ID', 'length', 'max'=>10),
			array('User_Comments', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rta_DB_ID, DB_ID_string, Material_ID, DBwriteActive, analysis_timespan, averaging_subinterval_secs, write_frequency, detector_ID, date_created, user_created, DB_counter, User_Comments', 'safe', 'on'=>'search'),
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
			'rta_DB_ID' => 'Rta Db',
			'DB_ID_string' => 'Db Id String',
			'Material_ID' => 'Material',
			'DBwriteActive' => 'Dbwrite Active',
			'analysis_timespan' => 'Analysis Timespan',
			'averaging_subinterval_secs' => 'Averaging Subinterval Secs',
			'write_frequency' => 'Write Frequency',
			'detector_ID' => 'Detector',
			'date_created' => 'Date Created',
			'user_created' => 'User Created',
			'DB_counter' => 'Db Counter',
			'User_Comments' => 'User Comments',
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

		$criteria->compare('rta_DB_ID',$this->rta_DB_ID);
		$criteria->compare('DB_ID_string',$this->DB_ID_string,true);
		$criteria->compare('Material_ID',$this->Material_ID,true);
		$criteria->compare('DBwriteActive',$this->DBwriteActive,true);
		$criteria->compare('analysis_timespan',$this->analysis_timespan,true);
		$criteria->compare('averaging_subinterval_secs',$this->averaging_subinterval_secs);
		$criteria->compare('write_frequency',$this->write_frequency,true);
		$criteria->compare('detector_ID',$this->detector_ID,true);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('user_created',$this->user_created,true);
		$criteria->compare('DB_counter',$this->DB_counter);
		$criteria->compare('User_Comments',$this->User_Comments,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RtaDbConfig the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
