<?php

/**
 * This is the model class for table "rm_element_composition".
 *
 * The followings are the available columns in table 'rm_element_composition':
 * @property integer $element_id
 * @property integer $source_id
 * @property string $element_name
 * @property string $element_value
 * @property integer $element_type
 * @property double $estimated_prob_error
 * @property double $estimated_max
 * @property double $estimated_min
 * @property string $update_timestamp
 */
class ElementComposition extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rm_element_composition';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('element_id, source_id, element_name, element_value, element_type, estimated_prob_error, estimated_max, estimated_min, update_timestamp', 'required'),
			array('element_id, source_id, element_type', 'numerical', 'integerOnly'=>true),
			array('estimated_prob_error, estimated_max, estimated_min', 'numerical'),
			array('element_name', 'length', 'max'=>100),
			array('element_value', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('element_id, source_id, element_name, element_value, element_type, estimated_prob_error, estimated_max, estimated_min, update_timestamp', 'safe', 'on'=>'search'),
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
			'element_id' => 'Element',
			'source_id' => 'Source',
			'element_name' => 'Element Name',
			'element_value' => 'Element Value',
			'element_type' => 'Element Type',
			'estimated_prob_error' => 'Estimated Prob Error',
			'estimated_max' => 'Estimated Max',
			'estimated_min' => 'Estimated Min',
			'update_timestamp' => 'Update Timestamp',
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

		$criteria->compare('element_id',$this->element_id);
		$criteria->compare('source_id',$this->source_id);
		$criteria->compare('element_name',$this->element_name,true);
		$criteria->compare('element_value',$this->element_value,true);
		$criteria->compare('element_type',$this->element_type);
		$criteria->compare('estimated_prob_error',$this->estimated_prob_error);
		$criteria->compare('estimated_max',$this->estimated_max);
		$criteria->compare('estimated_min',$this->estimated_min);
		$criteria->compare('update_timestamp',$this->update_timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ElementComposition the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
