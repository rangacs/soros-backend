<?php

/**
 * HighstockWidget  class file - seryi70's adaptation of
 * HighchartsWidget class file by
 *
 * @author Gavyn <gf010010@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version 0.1
 *
 * Use as follows:
 $this->Widget('ext.ActiveHighstock.HighstockWidget', array(
        'dataProvider'=>$dataProvider,
        'options'=> array(
    		'theme' => 'grid', //dark-blue dark-green gray grid skies
        'rangeSelector'=>array('selected'=>1),
    		'credits' => array('enabled' => true),
        'title'=>array('text'=>'Table title'),
    		'xAxis'=>array('maxZoom'=>'4 * 3600000' ),  //4 hours
    		'yAxis'=>array('title'=>array('text'=>'DataValueTitle')),
            'series'=>array(
                array(
                    'name'=>'SeriesTitle', //title of data
                    'dataResource'=>'data',     //data resource according to MySQL column 'data'
                    'dateResource'=>'time',     //data resource according to MySQL column 'time'
                )
            )
        )
    ));
 */

Yii::import('zii.widgets.CBaseListView');

class HighstockWidget extends CBaseListView {

	public $action = array();
	public $options = array();
	public $htmlOptions = array();      // keys: 'id' , etc, are added to the div tag inside the page source..

	/**
	 * Renders the widget.
	 * 
	 * Nov20th: 
	 *  i.) First sets up the div container elements (ie id, etc) which will hold our 'highstock' chart..
	 *              
	 */
	public function run() {
	 $id = $this->getId();               //Grabs the id (if available, ie 'wikkens-grid') or generates a new one ie 'yw0'..
	 $this->htmlOptions['id'] = $id;
   
	 echo CHtml::openTag('div', $this->htmlOptions);
	 echo CHtml::closeTag('div');

   parent::run();

	 // If the 'options' parameter is a JSON string, validate it first..
	 if(is_string($this->options)){
	  if(!$this->options = CJSON::decode($this->options))
	 			throw new CException('The options parameter is not valid JSON.');
	 }

	 // @var $defaultOptions: 
   //  Allow our php v.f. (viewfile) option 'id' (now $id in class) to mingle with our future 'page-source' options list..
	 $defaultOptions = array(
                       'chart' => array(
                                    'renderTo' => $id
                       ), 
                      
                       'exporting' => array(
                                        'enabled' => true
                       )
   );
   
   
   /*  If our php v.f. (viewfile) option 'option' is an array (which it is by default implementation)..
   *    REF: C:\Users\anotherComputerUser\Desktop\PHP_notes\Yii\phaeton_highstock\Nov20th_notes_to_self.txt
   *   
   *   CMap::mergeArray($toarray, $fromarray)  recursively merges both..
   *   
   *   So, going into the $defaultOptions array, 'chart' and 'exporting' elements 
   *     will be on the same level as ( going into the 'options' array ) elements: 'theme', 'rangeSelector', etc...   
   *    
   *   Here we are merging the php.vf array INTO our local class array $defaultOptions.. 
   *   AND giving it back the same name as before ( $this->options )...
   *                  
   */      
	 $this->options = CMap::mergeArray($defaultOptions, $this->options);
	 $jsOptions = CJavaScript::encode($this->options);                       //Encode it for js sake..
	 $this->registerScripts(__CLASS__ . '#' . $id, "$.chart = new Highcharts.StockChart($jsOptions);");

   $this->registerChartProcessScript($id);

   //self defined extra javascript
   $i  = 0;
   $cs = Yii::app()->clientScript;
   foreach($this->action as $ext){
    $cs->registerScript("ext".$i++, $ext, CClientScript::POS_BEGIN);
   }
        
	}//end run()..


    /*
    *  Nov20th: 
    *  Method  renderItems()  This method must be declared public, (inherited abstractedly from parent class)..        
    *
    *  This method is being automatically invoked somehow..
    *   CGridView also extends CBaseListView, and has similar auto-invoking behavior for its 'renderItems()' method..        
    */
    //public function renderItems(){} 
          
    public function renderItems(){
     $seriesNames = $this->dataProvider->model->attributeNames();          //Retrieve all column names from the model wrapper class..
     $data        = $this->dataProvider->getData();                        //Retrieve the data array (COMPLEX)..
     $series      = $this->options['series'];

     // [date,data] pairs              
     foreach($series as $i => $v){  //count($series) = 1..
      if( isset($v['dataResource']) && isset($v['dateResource']) && !is_array($v['dataResource']) && !is_array($v['dateResource']))
      {
       $seriesA = array();   
       foreach($data as $j => $val){     //count($data) = 10..
                    //sql UNIX_TIMESTAMP(s)-->JS UNIX timestamp(ms)
        $seriesN = array( 1000* floatval($val[ $v['dateResource'] ] ), floatval($val[ $v['dataResource'] ]) );   //$seriesN holds the values of 'time' and 'date' indexes of the $date[$v] array..
        $seriesA[] = $seriesN;           //Stuff $seriesA with several $seriesN subarrays..
       }
       
       /* Sort by first column (dates ascending order)
       *       (Modified -- for i loop --)
       *       (Original -- commented out --)
       */
       for($m=0; $m<count($seriesA); ++$m){
        $dates[$m] = $seriesA[$m][0];
       }                                  
       /*       
       foreach($seriesA as $key => $row){
        $dates[$key] = $row[0];
       }
       */
       
        array_multisort($dates, SORT_ASC, $seriesA);      //Sorts $dates from least to greatest (depending on value).. $seriesA multidimensional array is only key reindexed (starting at 0)..
        $this->options['series'][$i]['data'] = $seriesA;  //For each $i, add-in a 'data' element which points to our newly created $seriesA..
      }
     }// top foreach..
     //echo self::fb($this->options);      //debug only..
    }//end renderItems()..
    
    
    //Debug only..
    public static function fb($debug){
     echo Yii::trace(CVarDumper::dumpAsString($debug), 'vardump');
    }
  

	/**
	 * Publishes and registers the necessary script files.
	 *
	 * @param string the id of the script to be inserted into the page
	 * @param string the embedded script to be inserted into the page
	 */
	protected function registerScripts($id, $embeddedScript) {
		$basePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
		$baseUrl = Yii::app()->getAssetManager()->publish($basePath, false, 1, YII_DEBUG);
		$scriptFile = YII_DEBUG ? '/highstock.src.js' : '/highstock.js';

		$cs = Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile($baseUrl . $scriptFile);

		// register exporting module if enabled via the 'exporting' option
		if($this->options['exporting']['enabled']) {
			$scriptFile = YII_DEBUG ? 'exporting.src.js' : 'exporting.js';
			$cs->registerScriptFile("$baseUrl/modules/$scriptFile");
		}

		// register global theme if specified vie the 'theme' option
		if(isset($this->options['theme'])) {
			$scriptFile = $this->options['theme'] . ".js";
			$cs->registerScriptFile("$baseUrl/themes/$scriptFile");
		}
		$cs->registerScript($id, $embeddedScript, CClientScript::POS_LOAD);
	}

    protected function registerChartProcessScript($id){
		$basePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
		$baseUrl = Yii::app()->getAssetManager()->publish($basePath, false, 1, YII_DEBUG);
        $cs = Yii::app()->clientScript;

        //register highcharts script
        $cs->registerScript(__CLASS__.'#'.$id,"jQuery('#$id').highstockview();");

        //to remove the non-use element style options from the selected class
        //$cs->registerScript(__CLASS__.'#'.$id+1, "$('.highcharts-container').each(function(idx,el){el.style.position='';});", CClientScript::POS_LOAD);

        $cs->registerScriptFile($baseUrl.'/jquery.yiihighstockview.js',CClientScript::POS_END);
    }
}