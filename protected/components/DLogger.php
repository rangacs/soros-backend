<?php


//------------------------------------------------------------------------------
//! JsLogger
//------------------------------------------------------------------------------
class Logger{
	
	const INFO = 0;
	const DEBUG = 1;
	const WARNING = 2;
    const ERROR = 3;

	public $isDebugging;

    public function __construct() {
		
		/*if(isset($_REQUEST['debug'])){
		
			$this->isDebugging = true;
		}else{
			
			$this->isDebugging = false;
		}*/
	}
	
	public static 	 function jsLogger($loglevel='',$title,$data = array()){
		
		
		
	/*	if(!$this->isDebugging)
			return false;*/
		
		$jsonData = json_encode($data);
		
		switch($loglevel){
			
			
			case "error":
				echo "
				
						 <script type='text/javascript'> 
											console.log('".$title."');//Title
											console.table(".$jsonData.");//data
								</script>
				";			
			break;
			
			case "warn":
				echo "
				
						 <script type='text/javascript'> 
											console.log('".$title."');//Title
											console.table(".$jsonData.");//data
								</script>
				";			
			break;
			
			default:
				echo "
				
						 <script type='text/javascript'> 
											console.log('".$title."');//Title
											console.table(".$jsonData.");//data
								</script>
				";			
		}
		
		
		
		

		
	}

	
	public static function calibLogger($loglevel='',$title,$description){
		
		
		
		
		
		$calbMsgLogger = new CalibrationLogMessages();
		
		
		
			$calbMsgLogger->auto_calib_id = 1;
			$calbMsgLogger->error_type = $loglevel;
			$calbMsgLogger->title = $title;
			$calbMsgLogger->description = json_encode($description);
			$calbMsgLogger->updated_by = Yii::app()->user->id;
			$calbMsgLogger->save(false);
			
	}
}