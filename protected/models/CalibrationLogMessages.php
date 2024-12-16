<?php

/**
 * This is the model class for table "calibration_log_messages".
 *
 * The followings are the available columns in table 'calibration_log_messages':
 * @property integer $cal_msg_id
 * @property integer $auto_calib_id
 * @property integer $error_type
 * @property string $title
 * @property string $description
 * @property string $updated_at
 * @property integer $updated_by
 */
class CalibrationLogMessages extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'calibration_log_messages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('auto_calib_id, error_type, title, description, updated_at, updated_by', 'required'),
			array('auto_calib_id, error_type, updated_by', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cal_msg_id, auto_calib_id, error_type, title, description, updated_at, updated_by', 'safe', 'on'=>'search'),
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
			'cal_msg_id' => 'Cal Msg',
			'auto_calib_id' => 'Auto Calib',
			'error_type' => 'Error Type',
			'title' => 'Title',
			'description' => 'Description',
			'updated_at' => 'Updated At',
			'updated_by' => 'Updated By',
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

		$criteria->compare('cal_msg_id',$this->cal_msg_id);
		$criteria->compare('auto_calib_id',$this->auto_calib_id);
		$criteria->compare('error_type',$this->error_type);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('updated_by',$this->updated_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CalibrationLogMessages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
