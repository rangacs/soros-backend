<?php
 
 /*
 *  Abhinandan. 
 *   class StraightShotHelper
 *    Purpose: used by 'DashHelper::createDisplay_renderLogic()' method
 *    
 *    Used for storing the value of $j..    
 *
 */  
 class StraightShotHelper{
  public $bullseye = '1';
   
  public function __construct($arrow){
   $this->bullseye = $arrow;
   $this->grabBullseye();
  }
  
  public function grabBullseye(){
   return $this->bullseye;
  }
  
 }//end StraightShotHelper class..

?>
