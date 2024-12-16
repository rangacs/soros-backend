<?php

/**
 * This is the model class for table "wcl_health_log".
 *
 * The followings are the available columns in table 'wcl_health_log':
 * @property integer $wcl_a_id
 * @property string $wcl_plantCode
 * @property string $wcl_unloaderId
 * @property string $wcl_healthStatus
 * @property string $wcl_auto_tag_id
 * @property string $wcl_auto_tag_mCode
 * @property string $wcl_ash
 * @property string $wcl_moisture
 * @property string $wcl_sulfur
 * @property string $wcl_gcv
 * @property string $wcl_timestamp
 */
class HealthLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wcl_health_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('wcl_plantCode, wcl_unloaderId, wcl_healthStatus, wcl_ash, wcl_moisture, wcl_sulfur, wcl_gcv', 'length', 'max'=>50),
			array('wcl_auto_tag_id, wcl_auto_tag_mCode, wcl_timestamp', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('wcl_a_id, wcl_plantCode, wcl_unloaderId, wcl_healthStatus, wcl_auto_tag_id, wcl_auto_tag_mCode, wcl_ash, wcl_moisture, wcl_sulfur, wcl_gcv, wcl_timestamp', 'safe', 'on'=>'search'),
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
			//'wcl_a_id' => 'Wcl A',
			'wcl_plantCode' => 'Pl Code',
			'wcl_unloaderId' => 'Un Ld',
			'wcl_healthStatus' => 'Health',
			'wcl_auto_tag_id' => 'Trip',
			'wcl_auto_tag_mCode' => 'M Code',
			'wcl_ash' => 'Ash',
			'wcl_moisture' => 'Moisture',
			'wcl_sulfur' => 'Sulfur',
			'wcl_gcv' => 'Gcv',
			'wcl_timestamp' => 'Timestamp',
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

		$criteria->compare('wcl_a_id',$this->wcl_a_id);
		$criteria->compare('wcl_plantCode',$this->wcl_plantCode,true);
		$criteria->compare('wcl_unloaderId',$this->wcl_unloaderId,true);
		$criteria->compare('wcl_healthStatus',$this->wcl_healthStatus,true);
		$criteria->compare('wcl_auto_tag_id',$this->wcl_auto_tag_id,true);
		$criteria->compare('wcl_auto_tag_mCode',$this->wcl_auto_tag_mCode,true);
		$criteria->compare('wcl_ash',$this->wcl_ash,true);
		$criteria->compare('wcl_moisture',$this->wcl_moisture,true);
		$criteria->compare('wcl_sulfur',$this->wcl_sulfur,true);
		$criteria->compare('wcl_gcv',$this->wcl_gcv,true);
		$criteria->compare('wcl_timestamp',$this->wcl_timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HealthLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
