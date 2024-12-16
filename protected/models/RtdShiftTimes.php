<?php

/**
 * This is the model class for table "rtd_shift_times".
 *
 * The followings are the available columns in table 'rtd_shift_times':
 * @property string $shiftTimeID
 * @property string $shiftStart1
 * @property string $shiftDuration1
 * @property string $shiftStart2
 * @property string $shiftDuration2
 * @property string $shiftStart3
 * @property string $shiftDuration3
 */
class RtdShiftTimes extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rtd_shift_times';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shiftStart1, shiftDuration1, shiftStart2, shiftDuration2, shiftStart3, shiftDuration3', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('shiftTimeID, shiftStart1, shiftDuration1, shiftStart2, shiftDuration2, shiftStart3, shiftDuration3', 'safe', 'on'=>'search'),
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
			'shiftTimeID' => 'Shift Time',
			'shiftStart1' => 'Shift Start1',
			'shiftDuration1' => 'Shift Duration1',
			'shiftStart2' => 'Shift Start2',
			'shiftDuration2' => 'Shift Duration2',
			'shiftStart3' => 'Shift Start3',
			'shiftDuration3' => 'Shift Duration3',
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

		$criteria->compare('shiftTimeID',$this->shiftTimeID,true);
		$criteria->compare('shiftStart1',$this->shiftStart1,true);
		$criteria->compare('shiftDuration1',$this->shiftDuration1,true);
		$criteria->compare('shiftStart2',$this->shiftStart2,true);
		$criteria->compare('shiftDuration2',$this->shiftDuration2,true);
		$criteria->compare('shiftStart3',$this->shiftStart3,true);
		$criteria->compare('shiftDuration3',$this->shiftDuration3,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RtdShiftTimes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
