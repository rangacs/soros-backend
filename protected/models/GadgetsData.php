<?php

/**
 * This is the model class for table "gadlay_gadgets_data".
 *
 * The followings are the available columns in table 'gadlay_gadgets_data':
 * @property string $gadget_data_id
 * @property string $gadget_type
 * @property string $lay_id
 * @property string $gadget_name
 * @property string $gadget_size
 * @property integer $last_updated
 * @property string $data_source
 * @property string $detector_source
 *
 * The followings are the available model relations:
 * @property Charts[] $charts
 * @property Elements[] $elements
 * @property MasterPortlets $gadgetType
 * @property Layouts $lay
 * @property TableElements[] $tableElements
 */
class GadgetsData extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GadgetsData the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gadlay_gadgets_data';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gadget_type, lay_id, gadget_name, data_source, detector_source', 'required'),
			array('last_updated', 'numerical', 'integerOnly'=>true),
			array('gadget_type', 'length', 'max'=>20),
			array('lay_id', 'length', 'max'=>10),
			array('gadget_name', 'length', 'max'=>30),
			array('gadget_size', 'length', 'max'=>6),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('gadget_data_id, gadget_type, lay_id, gadget_name, gadget_size, last_updated, data_source, detector_source', 'safe', 'on'=>'search'),
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
			'charts' => array(self::HAS_MANY, 'Charts', 'gadget_data_id'),
			'elements' => array(self::HAS_MANY, 'Elements', 'gadget_data_id'),
			'gadgetType' => array(self::BELONGS_TO, 'MasterPortlets', 'gadget_type'),
			'lay' => array(self::BELONGS_TO, 'Layouts', 'lay_id'),
			'tableElements' => array(self::HAS_MANY, 'TableElements', 'gadget_data_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'gadget_data_id' => 'Gadget Data',
			'gadget_type' => 'Gadget Type',
			'lay_id' => 'Lay',
			'gadget_name' => 'Gadget Name',
			'gadget_size' => 'Gadget Size',
			'last_updated' => 'Last Updated',
			'data_source' => 'Data Source',
			'detector_source' => 'Detector Source',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('gadget_data_id',$this->gadget_data_id,true);
		$criteria->compare('gadget_type',$this->gadget_type,true);
		$criteria->compare('lay_id',$this->lay_id,true);
		$criteria->compare('gadget_name',$this->gadget_name,true);
		$criteria->compare('gadget_size',$this->gadget_size,true);
		$criteria->compare('last_updated',$this->last_updated);
		$criteria->compare('data_source',$this->data_source,true);
		$criteria->compare('detector_source',$this->detector_source,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}