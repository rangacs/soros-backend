<?php

/**
 * This is the model class for table "gadlay_elements".
 *
 * The followings are the available columns in table 'gadlay_elements':
 * @property integer $element_id
 * @property string $element_type
 * @property string $gadget_data_id
 * @property integer $order_location
 * @property string $element_colorset
 * @property string $element_setpoint
 * @property integer $show_value
 *
 * The followings are the available model relations:
 * @property GadlayGadgetsData $gadgetData
 */
class GadlayElements extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gadlay_elements';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('element_type, gadget_data_id, order_location, element_setpoint, show_value', 'required'),
			array('order_location, show_value', 'numerical', 'integerOnly'=>true),
			array('element_type', 'length', 'max'=>25),
			array('gadget_data_id', 'length', 'max'=>10),
			array('element_colorset', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('element_id, element_type, gadget_data_id, order_location, element_colorset, element_setpoint, show_value', 'safe', 'on'=>'search'),
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
			'gadgetData' => array(self::BELONGS_TO, 'GadlayGadgetsData', 'gadget_data_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'element_id' => 'Element',
			'element_type' => 'Element Type',
			'gadget_data_id' => 'Gadget Data',
			'order_location' => 'Order Location',
			'element_colorset' => 'Element Colorset',
			'element_setpoint' => 'Element Setpoint',
			'show_value' => 'Show Value',
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
		$criteria->compare('element_type',$this->element_type,true);
		$criteria->compare('gadget_data_id',$this->gadget_data_id,true);
		$criteria->compare('order_location',$this->order_location);
		$criteria->compare('element_colorset',$this->element_colorset,true);
		$criteria->compare('element_setpoint',$this->element_setpoint,true);
		$criteria->compare('show_value',$this->show_value);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GadlayElements the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
