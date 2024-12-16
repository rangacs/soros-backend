<?php

/**
 * This is the model class for table "ac_settings".
 *
 * The followings are the available columns in table 'ac_settings':
 * @property integer $ac_id
 * @property string $element_name
 * @property double $min
 * @property double $max
 * @property double $diff
 * @property double $max_offset_change
 * @property double $correction_pct
 * @property string $last_updated
 */
class AcSettings extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ac_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('element_name, min, max, diff, max_offset_change, correction_pct, last_updated', 'required'),
			array('min, max, diff, max_offset_change, correction_pct', 'numerical'),
			array('element_name', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ac_id, element_name, min, max, diff, max_offset_change, correction_pct, last_updated', 'safe', 'on'=>'search'),
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
			'ac_id' => 'Ac',
			'element_name' => 'Element Name',
			'min' => 'Min',
			'max' => 'Max',
			'diff' => 'Diff',
			'max_offset_change' => 'Max Offset Change',
			'correction_pct' => 'Correction Pct',
			'last_updated' => 'Last Updated',
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

		$criteria->compare('ac_id',$this->ac_id);
		$criteria->compare('element_name',$this->element_name,true);
		$criteria->compare('min',$this->min);
		$criteria->compare('max',$this->max);
		$criteria->compare('diff',$this->diff);
		$criteria->compare('max_offset_change',$this->max_offset_change);
		$criteria->compare('correction_pct',$this->correction_pct);
		$criteria->compare('last_updated',$this->last_updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AcSettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
