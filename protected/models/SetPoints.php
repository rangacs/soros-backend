<?php

/**
 * This is the model class for table "rm_set_points".
 *
 * The followings are the available columns in table 'rm_set_points':
 * @property integer $sp_id
 * @property integer $product_id
 * @property string $sp_name
 * @property string $sp_value_num
 * @property string $sp_value_den
 * @property string $sp_const_value_num
 * @property string $sp_const_value_den
 * @property string $sp_tolerance_ulevel
 * @property string $sp_tolerance_llevel
 * @property string $sp_weight
 * @property integer $sp_status
 * @property integer $sp_priority
 */
class SetPoints extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rm_set_points';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sp_id, product_id, sp_status, sp_priority', 'numerical', 'integerOnly'=>true),
			array('sp_name', 'length', 'max'=>100),
			array('sp_value_num, sp_value_den, sp_const_value_num, sp_const_value_den, sp_tolerance_ulevel, sp_tolerance_llevel, sp_weight', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sp_id, product_id, sp_name, sp_value_num, sp_value_den, sp_const_value_num, sp_const_value_den, sp_tolerance_ulevel, sp_tolerance_llevel, sp_weight, sp_status, sp_priority', 'safe', 'on'=>'search'),
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
			'sp_id' => 'Sp',
			'product_id' => 'Product',
			'sp_name' => 'Sp Name',
			'sp_value_num' => 'Sp Value Num',
			'sp_value_den' => 'Sp Value Den',
			'sp_const_value_num' => 'Sp Const Value Num',
			'sp_const_value_den' => 'Sp Const Value Den',
			'sp_tolerance_ulevel' => 'Sp Tolerance Ulevel',
			'sp_tolerance_llevel' => 'Sp Tolerance Llevel',
			'sp_weight' => 'Sp Weight',
			'sp_status' => 'Sp Status',
			'sp_priority' => 'Sp Priority',
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

		$criteria->compare('sp_id',$this->sp_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('sp_name',$this->sp_name,true);
		$criteria->compare('sp_value_num',$this->sp_value_num,true);
		$criteria->compare('sp_value_den',$this->sp_value_den,true);
		$criteria->compare('sp_const_value_num',$this->sp_const_value_num,true);
		$criteria->compare('sp_const_value_den',$this->sp_const_value_den,true);
		$criteria->compare('sp_tolerance_ulevel',$this->sp_tolerance_ulevel,true);
		$criteria->compare('sp_tolerance_llevel',$this->sp_tolerance_llevel,true);
		$criteria->compare('sp_weight',$this->sp_weight,true);
		$criteria->compare('sp_status',$this->sp_status);
		$criteria->compare('sp_priority',$this->sp_priority);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SetPoints the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
