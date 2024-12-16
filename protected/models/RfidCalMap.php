<?php

/**
 * This is the model class for table "wcl_rfid_cal_map".
 *
 * The followings are the available columns in table 'wcl_rfid_cal_map':
 * @property integer $wcl_acid
 * @property string $wcl_item_code
 * @property string $wcl_item_namev
 * @property string $wcl_sabia_cal_name
 * @property string $wcl_updated
 */
class RfidCalMap extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wcl_rfid_cal_map';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('wcl_item_code, wcl_item_namev, wcl_sabia_cal_name, wcl_updated', 'required'),
			array('wcl_item_code, wcl_item_namev, wcl_sabia_cal_name', 'length', 'max'=>100),
			array('wcl_updated', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('wcl_acid, wcl_item_code, wcl_item_namev, wcl_sabia_cal_name, wcl_updated', 'safe', 'on'=>'search'),
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
			'wcl_acid' => 'Wcl Acid',
			'wcl_item_code' => 'Wcl Item Code',
			'wcl_item_namev' => 'Wcl Item Namev',
			'wcl_sabia_cal_name' => 'Wcl Sabia Cal Name',
			'wcl_updated' => 'Wcl Updated',
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

		$criteria->compare('wcl_acid',$this->wcl_acid);
		$criteria->compare('wcl_item_code',$this->wcl_item_code,true);
		$criteria->compare('wcl_item_namev',$this->wcl_item_namev,true);
		$criteria->compare('wcl_sabia_cal_name',$this->wcl_sabia_cal_name,true);
		$criteria->compare('wcl_updated',$this->wcl_updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RfidCalMap the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
