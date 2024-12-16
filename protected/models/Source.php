<?php

/**
 * This is the model class for table "rm_source".
 *
 * The followings are the available columns in table 'rm_source':
 * @property integer $src_id
 * @property string $src_name
 * @property string $src_type
 * @property string $product_id
 * @property string $src_priority
 * @property string $src_distance
 * @property string $src_delay
 * @property string $src_min_feedrate
 * @property string $src_max_feedrate
 * @property string $src_proposed_feedrate
 * @property string $src_measured_feedrate
 * @property string $src_cost
 * @property string $src_status
 */
class Source extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rm_source';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('src_name, src_type, product_id, src_priority, src_distance, src_delay', 'length', 'max'=>100),
			array('src_min_feedrate, src_max_feedrate,src_measured_feedrate, src_cost, src_status_mode', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('src_id, src_name, src_type, product_id, src_priority, src_distance, src_delay, src_min_feedrate, src_max_feedrate, src_proposed_feedrate, src_measured_feedrate, src_cost, src_status', 'safe', 'on'=>'search'),
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
			'src_id' => 'Src',
			'src_name' => 'Src Name',
			'src_type' => 'Src Type',
			'product_id' => 'Product',
			'src_priority' => 'Src Priority',
			'src_distance' => 'Src Distance',
			'src_delay' => 'Src Delay',
			'src_min_feedrate' => 'Src Min Feedrate',
			'src_max_feedrate' => 'Src Max Feedrate',
			'src_proposed_feedrate' => 'Src Proposed Feedrate',
			'src_measured_feedrate' => 'Src Measured Feedrate',
			'src_cost' => 'Src Cost',
			'src_status_mode' => 'Src Status',
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

		$criteria->compare('src_id',$this->src_id);
		$criteria->compare('src_name',$this->src_name,true);
		$criteria->compare('src_type',$this->src_type,true);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('src_priority',$this->src_priority,true);
		$criteria->compare('src_distance',$this->src_distance,true);
		$criteria->compare('src_delay',$this->src_delay,true);
		$criteria->compare('src_min_feedrate',$this->src_min_feedrate,true);
		$criteria->compare('src_max_feedrate',$this->src_max_feedrate,true);
		$criteria->compare('src_proposed_feedrate',$this->src_proposed_feedrate,true);
		$criteria->compare('src_measured_feedrate',$this->src_measured_feedrate,true);
		$criteria->compare('src_cost',$this->src_cost,true);
		$criteria->compare('src_status_mode',$this->src_status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Source the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
