<?php

/**
 * This is the model class for table "profile_log".
 *
 * The followings are the available columns in table 'profile_log':
 * @property integer $product_id
 * @property string $product_name
 * @property integer $user_id
 * @property string $source
 * @property string $status
 * @property string $elements
 * @property string $setpoints
 * @property string $created_on
 * @property string $created_by
 * @property string $updated_on
 * @property string $updated_by
 * @property integer $deleted_on
 * @property string $deleted_by
 */
class ProfileLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rm_profile_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, product_name, user_id, source, status, elements, setpoints, created_on, created_by, updated_on, updated_by, deleted_on, deleted_by', 'required'),
			array('product_id, user_id, deleted_on', 'numerical', 'integerOnly'=>true),
			array('product_name', 'length', 'max'=>200),
			array('status', 'length', 'max'=>20),
			array('created_by, updated_by, deleted_by', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('product_id, product_name, user_id, source, status, elements, setpoints, created_on, created_by, updated_on, updated_by, deleted_on, deleted_by', 'safe', 'on'=>'search'),
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
			'product_id' => 'Product',
			'product_name' => 'Product Name',
			'user_id' => 'User',
			'source' => 'Source',
			'status' => 'Status',
			'elements' => 'Elements',
			'setpoints' => 'Setpoints',
			'created_on' => 'Created On',
			'created_by' => 'Created By',
			'updated_on' => 'Updated On',
			'updated_by' => 'Updated By',
			'deleted_on' => 'Deleted On',
			'deleted_by' => 'Deleted By',
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

		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('source',$this->source,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('elements',$this->elements,true);
		$criteria->compare('setpoints',$this->setpoints,true);
		$criteria->compare('created_on',$this->created_on,true);
		$criteria->compare('created_by',$this->created_by,true);
		$criteria->compare('updated_on',$this->updated_on,true);
		$criteria->compare('updated_by',$this->updated_by,true);
		$criteria->compare('deleted_on',$this->deleted_on);
		$criteria->compare('deleted_by',$this->deleted_by,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
          protected function beforeSave()
            {
                if(parent::beforeSave())
                {
                    if($this->isNewRecord)
                    {
                        $this->created_on=$this->updated_on=new CDbExpression('NOW()');
                        $this->created_by=$this->updated_by=Yii::app()->user->id;
                    }
                    else{
                        $this->updated_by=Yii::app()->user->id;
                        $this->updated_on=new CDbExpression('NOW()');
                    }
                    return true;
                }
                else
                    return false;
            }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProfileLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
