<?php

/**
 * This is the model class for table "rta_tag_index_queued".
 *
 * The followings are the available columns in table 'rta_tag_index_queued':
 * @property string $tagID
 * @property string $rtaMasterID
 * @property string $status
 * @property string $hasMerge
 * @property string $tagName
 * @property string $tagGroupID
 * @property string $LocalstartTime
 * @property string $LocalendTime
 * @property string $createdOn
 * @property string $createdBy
 * @property string $updatedOn
 * @property string $updatedBy
 */
class RtaTagIndexQueued extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rta_tag_index_queued';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rtaMasterID, status, tagName, tagGroupID, createdBy, updatedBy', 'required'),
			array('rtaMasterID', 'length', 'max'=>10),
			array('status', 'length', 'max'=>9),
			array('hasMerge', 'length', 'max'=>5),
			array('tagName', 'length', 'max'=>30),
			array('tagGroupID, createdBy, updatedBy', 'length', 'max'=>4),
			array('LocalstartTime, LocalendTime, createdOn, updatedOn', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tagID, rtaMasterID, status, hasMerge, tagName, tagGroupID, LocalstartTime, LocalendTime, createdOn, createdBy, updatedOn, updatedBy', 'safe', 'on'=>'search'),
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
			'tagID' => 'Tag',
			'rtaMasterID' => 'Rta Master',
			'status' => 'Status',
			'hasMerge' => 'Has Merge',
			'tagName' => 'Tag Name',
			'tagGroupID' => 'Tag Group',
			'LocalstartTime' => 'Localstart Time',
			'LocalendTime' => 'Localend Time',
			'createdOn' => 'Created On',
			'createdBy' => 'Created By',
			'updatedOn' => 'Updated On',
			'updatedBy' => 'Updated By',
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

		$criteria->compare('tagID',$this->tagID,true);
		$criteria->compare('rtaMasterID',$this->rtaMasterID,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('hasMerge',$this->hasMerge,true);
		$criteria->compare('tagName',$this->tagName,true);
		$criteria->compare('tagGroupID',$this->tagGroupID,true);
		$criteria->compare('LocalstartTime',$this->LocalstartTime,true);
		$criteria->compare('LocalendTime',$this->LocalendTime,true);
		$criteria->compare('createdOn',$this->createdOn,true);
		$criteria->compare('createdBy',$this->createdBy,true);
		$criteria->compare('updatedOn',$this->updatedOn,true);
		$criteria->compare('updatedBy',$this->updatedBy,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RtaTagIndexQueued the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
