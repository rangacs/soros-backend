<?php

/**
 * This is the model class for table "tbl_audit_trail_master_template_iii".
 *
 * The followings are the available columns in table 'tbl_audit_trail_master_template_iii':
 * @property string $id
 * @property string $_key_
 *
 * The followings are the available model relations:
 * @property TblAuditTrailChildTemplateIii[] $tblAuditTrailChildTemplateIiis
 */
class MasterTemplateiii extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MasterTemplateiii the static model class
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
		return 'tbl_audit_trail_master_template_iii';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('_key_', 'required'),
			array('_key_', 'length', 'max'=>120),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, _key_', 'safe', 'on'=>'search'),
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
			'tblAuditTrailChildTemplateIiis' => array(self::HAS_MANY, 'TblAuditTrailChildTemplateIii', 'child_key_'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'_key_' => 'Key',
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
		$criteria->compare('_key_',$this->_key_,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
  
  
  /*
  *   Method:  dupExists()
  *   
  *  Purpose: Checks for duplicates in master template table..    
  *
  *  @return  TRUE   duplicate exists
  *  @return  FALSE  No previous _key_ value exists 
  *     
  */      
  public function dupExists($key){
   $command = Yii::app()->db->createCommand("SELECT _key_ FROM sabia_helios_v2_db.tbl_audit_trail_master_template_iii WHERE _key_ = '$key' ");     
   $rs = $command->query();
   if( count($rs) > 0 )
   {
    return TRUE;
   }
   elseif( count($rs) < 1 )
   {
    return FALSE;
   }
  }//end dupExists()..
  
  
  /*
  *   Method:  buildChildTemplate()
  *
  *   @params  POST  from MasterTemplateiii Controller
  *   
  *   @return  array  $child_arr_proper    
  */ 
  public function buildChildTemplate($key, $action, $controller, $category, $priority, $message_type){
   $child_arr = array();   
    foreach( $_POST['MasterTemplateiii'] as $k1 => $v1 ){    
     if($k1 == 'category')
     {
      $child_arr['category'] = $v1;
     }
     if($k1 == 'priority')
     {
      $child_arr['priority'] = $v1;
     }
     if($k1 == 'message_type')
     {
      $child_arr['message_type'] = $v1;               
     }
    }//end foreach stuff child_arr..
              
    $child_value = serialize($child_arr);
      
    $child_arr_proper = array(
                          'child_key_' => $key,
                          'action'     => $action,
                          'controller' => $controller,
                          'value'      => $child_value
    );
    return $child_arr_proper;
  }//end buildChildTemplate().. 
  
  
  
  /*
  *  The CActiveRecord __construct will attach behaviors (returned by this method)..
  *
  *  Method  behaviors()  Associated with AuditTrail Module..
  *   * Placed here to allow logging when DB gator table is affected..    
  */    
  public function behaviors(){  
   return array( 
            'LoggableBehavior' => 'application.modules.auditTrail.behaviors.LoggableBehavior', 
   ); 
 }    
  
}