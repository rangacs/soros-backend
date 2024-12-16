<?php

/**
 *  @Class  CDbCacheSession � CDbCache � CCache � CApplicationComponent � CComponent 
 *  @author Abhinandan. <droman@sabiainc.com> 
 *  @Purpose: To over-ride CDbCache methods, for our custom-made table.
 *  
 */
class CDbCacheSession extends CDbCache
{	
	public function init()
	{
		parent::init();
	}
  
  
  /*
  *  Native method; inherited from CDbCache (parent)
  *  
  *   Purpose: Peforms over-write functionality of any 
  *             existing key, then writes new info to that key.  
  *   @return New Method: cs_addValue()       
  *
  */    
  protected function setValue($key,$value,$expire)
	{
		$this->deleteValue($key);
		return $this->cs_addValue($key,$value,$expire);  //NEED TO define alias here..
	}
  
  
  /*
  *  New Method: cs_addValue()
  *   i.)  Over-rides parent 'addValue()' .  
  *   ii.) Adds in new values for modified table structure.  
  *   
  *   cs_addValue($key,$value,$expire)  
  */    
  protected function cs_addValue($key,$value,$expire)
	{
		if(!$this->_gced && mt_rand(0,1000000)<$this->_gcProbability)
		{
			$this->gc();
			$this->_gced=true;
		}

		if($expire>0)
    {
     $expire+=time();
    }	
		else
			$expire=0;
		$sql="INSERT INTO {$this->cacheTableName} (id, expire, value) VALUES ('$key', $expire, :value)";
		try
		{
			$command=$this->getDbConnection()->createCommand($sql);
			$command->bindValue(':value', $value, PDO::PARAM_LOB);
			$command->execute();
			return true;
		}
		catch(Exception $e)
		{
			return false;
		}
	}
  
  
  /*
  *  New Method:  public  cs_updateRecord() 
  *   i.) Purpose: Updates the 'alias' field.  
  *
  *
  *   DAVE, you need to over-ride the $prefixKey found in ***    
  *   STEPS:
  *     a.) Internally fetc  
  *       
  */
  public function cs_updateRecord($alias, $c_a, $language)
	{
   $hash = $this->generateUniqueKey($alias);
   $sql="UPDATE {$this->cacheTableName} SET alias = :alias, c_a = :c_a, language = :language WHERE id='$hash' ";    
   try
	 {
	 	$command = $this->getDbConnection()->createCommand($sql);                        
	 	$command->bindValue(':alias', $alias, PDO::PARAM_LOB); 
    $command->bindValue(':c_a', $c_a, PDO::PARAM_LOB); 
    $command->bindValue(':language', $language, PDO::PARAM_LOB);                        
	  $command->execute();                                                         
	  return true;
	 }
	 catch(Exception $e)
	 {
	  return false;
	 }
  }
  /*
  {
    Controller::fb('CDbCacheSession, cs_updateRecord(), here!! ');
		//$sql="UPDATE {$this->cacheTableName} SET alias = :value WHERE id = '$key' ";    //LEFT OFF, FOR NOW...
    try
		{
			//$command=$this->getDbConnection()->createCommand($sql);                         //LEFT OFF, FOR NOW..
		//$command->bindValue(':value',$value,PDO::PARAM_LOB);                           //LEFT OFF, FOR NOW..
			//$command->execute();                                                         //LEFT OFF, FOR NOW..
			return true;
		}
		catch(Exception $e)
		{
			return false;
		}
	}
  */
  
  
  
  /*
  *  Method c_getValue()
  *   Purpose: Get the value from the cache
  *   
  *  @param $key  The key whose value we wish to get.
  *  
  *  @return array  The array containing the target key,value pair.            
  *
  */    
  public function c_getValue($key)
  {
   $db  = $this->getDbConnection();
   $sql = "SELECT value FROM {$this->cacheTableName} WHERE id='$key' ";
   $command = Yii::app()->db->createCommand($sql);
   $rs = $command->query();
   return $rs;
  }
  
  //Dave place added methods here...

}
