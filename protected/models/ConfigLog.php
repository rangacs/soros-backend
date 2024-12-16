<?php

/**
 * This is the model class for table "rm_config_log".
 *
 * The followings are the available columns in table 'rm_config_log':
 * @property integer $cid
 * @property string $c_table_name
 * @property string $c_var_name
 * @property string $c_var_value
 * @property string $c_var_desc
 * @property string $c_updated
 */
class ConfigLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rm_config_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('c_table_name, c_var_name, c_var_value, c_var_desc, c_updated', 'required'),
			array('c_table_name, c_var_name', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cid, c_table_name, c_var_name, c_var_value, c_var_desc, c_updated', 'safe', 'on'=>'search'),
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
			'cid' => 'Cid',
			'c_table_name' => 'C Table Name',
			'c_var_name' => 'C Var Name',
			'c_var_value' => 'C Var Value',
			'c_var_desc' => 'C Var Desc',
			'c_updated' => 'C Updated',
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

		$criteria->compare('cid',$this->cid);
		$criteria->compare('c_table_name',$this->c_table_name,true);
		$criteria->compare('c_var_name',$this->c_var_name,true);
		$criteria->compare('c_var_value',$this->c_var_value,true);
		$criteria->compare('c_var_desc',$this->c_var_desc,true);
		$criteria->compare('c_updated',$this->c_updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ConfigLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
