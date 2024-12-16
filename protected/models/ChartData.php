<?php

/**
 * This is the model class for table "chart_data".
 *
 * The followings are the available columns in table 'chart_data':
 * @property string $id
 * @property integer $time
 * @property double $data
 */
class ChartData extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ChartData the static model class
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
		return 'chart_data';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('time', 'numerical', 'integerOnly'=>true),
			array('data', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, time, data', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'time' => 'Time',
			'data' => 'Data',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('time',$this->time);
		$criteria->compare('data',$this->data);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
  
  
  /*
  *  Nov 12th:
  *  
  *  Purpose: Custom method for retrieving all records from 'chart_data' db table..    
  *
  */    
  public function retrieveAll()
  {
   $criteria = new CDbCriteria;
   
   $criteria->alias = 'cd';
   $criteria->select = 'cd.id, cd.time, cd.data';
  
   return new CActiveDataProvider($this, array(
                                          'criteria' => $criteria
   ));
  
  }//end retrieveAll()..
  
  
  
  /*
  *  Nov 12th: Pulls last 5min data from db 'chart_data' db table..    
  *
  *  @params:  $current  timestamp  
  */    
  public function retrieve5min($current)
  {
   $criteria = new CDbCriteria;
   
   $criteria->alias = 'cd';
   $criteria->select = 'cd.id, cd.time, cd.data';
  
   return new CActiveDataProvider($this, array(
                                          'criteria' => $criteria
   ));
  
  }//end retrieveAll()..
  
  
  /*
  *  Nothing special here.. would like to pull only first record for demonstration purposes only..
  *
  */    
  public function m_pullFirst()
  {
   $criteria = new CDbCriteria;
   
   $criteria->alias = 'cd';
   $criteria->select = 'cd.id, cd.time, cd.data';
  
   return new CActiveDataProvider($this, array(
                                          'criteria' => $criteria
   ));
  }
  
  
  /*
  *   Method:  m_fiveMinLogic()
  *
  *    @params  $start_time  The current time
  *    @params  $end_time    The time already occurred in the past
  *    
  *    $command:   
  *     | $end_time | <--records we want--> | $start_time | 
  *             
  */      
  public function m_fiveMinLogic($start_time, $end_time)
  {
   $command = Yii::app()->db->createCommand("SELECT 
                                              cd.id,
                                              cd.time,
                                              cd.data
                                             FROM highstock.chart_data AS cd
                                             WHERE time <= '$start_time' AND time >= '$end_time' ");      
                                                                                        
   $rs = $command->query();
   if( count($rs) > 0 )      //If we are within our mark..
   {
    //Build Identical query using CDbCriteria..
    //@return CActiveDataProvider
    $criteria = new CDbCriteria;
   
    $criteria->alias     = 'cd';
    $criteria->select    = 'cd.id, cd.time, cd.data';
    $criteria->condition = " time <= '$start_time' AND time >= '$end_time' ";
  
    return new CActiveDataProvider($this, array(
                                          'criteria' => $criteria
    ));
    
   }
   elseif( count($rs) < 1 )  //If outside our mark.. 
   {
    //Pull 10x (Yii default) most recent records using CDbCriteria..
    //@return CActiveDataProvider
    $criteria = new CDbCriteria;
   
    $criteria->alias     = 'cd';
    $criteria->select    = 'cd.id, cd.time, cd.data';
  
    return new CActiveDataProvider($this, array(
                                          'criteria' => $criteria
    ));
  
   }   
  
  }
  
  
  /*
  *   Method:  m_tenMinLogic()
  *
  *    @params  $start_time  The current time
  *    @params  $end_time    The time already occurred in the past
  *    
  *    $command:   
  *     | $end_time | <--records we want--> | $start_time | 
  *             
  */      
  public function m_tenMinLogic($start_time, $end_time)
  {
   $command = Yii::app()->db->createCommand("SELECT 
                                              cd.id,
                                              cd.time,
                                              cd.data
                                             FROM highstock.chart_data AS cd
                                             WHERE time <= '$start_time' AND time >= '$end_time' ");    
                                                                                        
   $rs = $command->query();
   
   if( count($rs) > 0 )      //If we are within our mark..
   {
    //Build Identical query using CDbCriteria..
    //@return CActiveDataProvider
    $criteria = new CDbCriteria;
   
    $criteria->alias     = 'cd';
    $criteria->select    = 'cd.id, cd.time, cd.data';
    $criteria->condition = " time <= '$start_time' AND time >= '$end_time' ";
    
  
    return new CActiveDataProvider($this, array(
                                          'criteria' => $criteria
    ));
    
   }
   elseif( count($rs) < 1 )  //If outside our mark.. 
   {
    //Pull 10x (Yii default) most recent records using CDbCriteria..
    //@return CActiveDataProvider
    $criteria = new CDbCriteria;
   
    $criteria->alias     = 'cd';
    $criteria->select    = 'cd.id, cd.time, cd.data';
    //$criteria->order     = 'cd.time DESC';             //Firefox browser error: "bBox is undefined" ..
    $criteria->order     = 'cd.time ASC';                //WORKS!!
  
    return new CActiveDataProvider($this, array(
                                          'criteria' => $criteria
    ));
  
   }   
  
  }
  
  
}