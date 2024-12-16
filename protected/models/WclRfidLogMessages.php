<?php

/**
 * This is the model class for table "wcl_rfid_log_messages".
 *
 * The followings are the available columns in table 'wcl_rfid_log_messages':
 * @property integer $logid
 * @property string $message_type
 * @property string $short_descrip
 * @property string $long_descrip
 * @property string $trip_id
 * @property string $unloaderID
 * @property string $vehNo
 * @property integer $flag
 * @property string $timestamp
 */
class WclRfidLogMessages extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wcl_rfid_log_messages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('message_type, short_descrip, long_descrip, trip_id, unloaderID, vehNo, flag, timestamp', 'required'),
			array('flag', 'numerical', 'integerOnly'=>true),
			array('message_type', 'length', 'max'=>10),
			array('short_descrip', 'length', 'max'=>255),
			array('trip_id', 'length', 'max'=>75),
			array('unloaderID', 'length', 'max'=>50),
			array('vehNo', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('logid, message_type, short_descrip, long_descrip, trip_id, unloaderID, vehNo, flag, timestamp', 'safe', 'on'=>'search'),
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
			//'logid' => 'Logid',
			'message_type' => 'Message Type',
			//'short_descrip' => 'Short Descrip',
			'long_descrip' => 'Long Descrip',
			'trip_id' => 'Trip',
			'unloaderID' => 'Unloader',
			//'vehNo' => 'Veh No',
			//'flag' => 'Flag',
			'timestamp' => 'Timestamp',
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

		$criteria->compare('logid',$this->logid);
		$criteria->compare('message_type',$this->message_type,true);
		$criteria->compare('short_descrip',$this->short_descrip,true);
		$criteria->compare('long_descrip',$this->long_descrip,true);
		$criteria->compare('trip_id',$this->trip_id,true);
		$criteria->compare('unloaderID',$this->unloaderID,true);
		$criteria->compare('vehNo',$this->vehNo,true);
		$criteria->compare('flag',$this->flag);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WclRfidLogMessages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
