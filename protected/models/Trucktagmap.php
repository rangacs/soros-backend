<?php

/**
 * This is the model class for table "wcl_trucktagmap".
 *
 * The followings are the available columns in table 'wcl_trucktagmap':
 * @property integer $wcl_map_trtagId
 * @property string $wcl_map_trId
 * @property string $wcl_map_tagId
 */
class Trucktagmap extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wcl_truckTagMap';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('wcl_map_trId, wcl_map_tagId', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('wcl_map_trtagId, wcl_map_trId, wcl_map_tagId', 'safe', 'on'=>'search'),
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
			'wcl_map_trtagId' => 'Wcl Map Trtag',
			'wcl_map_trId' => 'Wcl Map Tr',
			'wcl_map_tagId' => 'Wcl Map Tag',
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

		$criteria->compare('wcl_map_trtagId',$this->wcl_map_trtagId);
		$criteria->compare('wcl_map_trId',$this->wcl_map_trId,true);
		$criteria->compare('wcl_map_tagId',$this->wcl_map_tagId,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Trucktagmap the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
