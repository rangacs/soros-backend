<?php

/**
 * This is the model class for table "wcl_truckInfo".
 *
 * The followings are the available columns in table 'wcl_truckInfo':
 * @property integer $w_tripID
 * @property string $w_vehNo
 * @property string $w_plantCode
 * @property string $w_unloaderID
 * @property string $w_matCode
 * @property string $w_matName
 * @property string $w_suppCode
 * @property string $w_suppName
 * @property string $w_traCode
 * @property string $w_traName
 * @property string $w_loadCity
 * @property double $w_chQty
 * @property string $w_timestamp
 */
class TruckInfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wcl_truckInfo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('w_tripID', 'numerical'),
			array('w_chQty', 'numerical'),
			array('w_vehNo, w_plantCode, w_unloaderID, w_matCode, w_matName, w_suppCode, w_suppName, w_traCode, w_traName, w_loadCity', 'length', 'max'=>100),
			array('w_timestamp', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('w_tripID, w_vehNo, w_plantCode, w_unloaderID, w_matCode, w_matName, w_suppCode, w_suppName, w_traCode, w_traName, w_loadCity, w_chQty, w_timestamp', 'safe', 'on'=>'search'),
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
			'w_tripID' => 'Trip ID',
			'w_vehNo' => 'W Veh No',
			'w_plantCode' => 'W Plant Code',
			'w_unloaderID' => 'W Unloader',
			'w_matCode' => 'W Mat Code',
			'w_matName' => 'W Mat Name',
			'w_suppCode' => 'W Supp Code',
			'w_suppName' => 'W Supp Name',
			'w_traCode' => 'W Tra Code',
			'w_traName' => 'W Tra Name',
			'w_loadCity' => 'W Load City',
			'w_chQty' => 'Challan Quantity',
			'w_timestamp' => 'W Timestamp',
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

		$criteria->compare('w_tripID',$this->w_tripID);
		$criteria->compare('w_vehNo',$this->w_vehNo,true);
		$criteria->compare('w_plantCode',$this->w_plantCode,true);
		$criteria->compare('w_unloaderID',$this->w_unloaderID,true);
		$criteria->compare('w_matCode',$this->w_matCode,true);
		$criteria->compare('w_matName',$this->w_matName,true);
		$criteria->compare('w_suppCode',$this->w_suppCode,true);
		$criteria->compare('w_suppName',$this->w_suppName,true);
		$criteria->compare('w_traCode',$this->w_traCode,true);
		$criteria->compare('w_traName',$this->w_traName,true);
		$criteria->compare('w_loadCity',$this->w_loadCity,true);
		$criteria->compare('w_chQty',$this->w_chQty);
		$criteria->compare('w_timestamp',$this->w_timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TruckInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
