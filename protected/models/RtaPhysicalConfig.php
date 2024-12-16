<?php

/**
 * This is the model class for table "rta_physical_config".
 *
 * The followings are the available columns in table 'rta_physical_config':
 * @property integer $rta_ID_physical
 * @property string $rtaMasterID
 * @property string $IPaddress
 * @property string $goodDataSecondsWeight_physicalCfg
 * @property string $massflowWeight_physicalCfg
 * @property string $analysis_timespan
 * @property integer $averaging_subinterval_secs
 * @property string $detectorID
 */
class RtaPhysicalConfig extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rta_physical_config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rtaMasterID, IPaddress', 'required'),
			array('averaging_subinterval_secs', 'numerical', 'integerOnly'=>true),
			array('rtaMasterID, analysis_timespan, detectorID', 'length', 'max'=>10),
			array('IPaddress', 'length', 'max'=>20),
			array('goodDataSecondsWeight_physicalCfg, massflowWeight_physicalCfg', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rta_ID_physical, rtaMasterID, IPaddress, goodDataSecondsWeight_physicalCfg, massflowWeight_physicalCfg, analysis_timespan, averaging_subinterval_secs, detectorID', 'safe', 'on'=>'search'),
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
			'rta_ID_physical' => 'Rta Id Physical',
			'rtaMasterID' => 'Rta Master',
			'IPaddress' => 'Ipaddress',
			'goodDataSecondsWeight_physicalCfg' => 'Good Data Seconds Weight Physical Cfg',
			'massflowWeight_physicalCfg' => 'Massflow Weight Physical Cfg',
			'analysis_timespan' => 'Analysis Timespan',
			'averaging_subinterval_secs' => 'Averaging Subinterval Secs',
			'detectorID' => 'Detector',
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

		$criteria->compare('rta_ID_physical',$this->rta_ID_physical);
		$criteria->compare('rtaMasterID',$this->rtaMasterID,true);
		$criteria->compare('IPaddress',$this->IPaddress,true);
		$criteria->compare('goodDataSecondsWeight_physicalCfg',$this->goodDataSecondsWeight_physicalCfg,true);
		$criteria->compare('massflowWeight_physicalCfg',$this->massflowWeight_physicalCfg,true);
		$criteria->compare('analysis_timespan',$this->analysis_timespan,true);
		$criteria->compare('averaging_subinterval_secs',$this->averaging_subinterval_secs);
		$criteria->compare('detectorID',$this->detectorID,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RtaPhysicalConfig the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
