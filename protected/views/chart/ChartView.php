<?php
/* @var $this ChartController */

$this->breadcrumbs=array(
	'Chart'=>array('/chart'),
	'Index',
);
?>

 <h1>
  <?php 
   //echo $this->id;
   echo "Helios Analyzer Data Playback";
  ?>
 </h1>

 <p>
	<!-- You may change the content of this page by modifying
	the file <tt> -->  <?php //echo __FILE__; ?> <!-- </tt> -->
 </p>


 <!-- Nov10th -->
 <div class="form">
<?php echo CHtml::beginForm(); ?>
 <?php echo CHtml::hiddenField( 'auto_poll', 'no' ); ?>
 
 <?php echo CHtml::hiddenField( 'stop_poll', 'yes' ); ?>
<div class="row">


<script>

 var auto_poll = "";   //Dynamic variable..
 
 
 function getFuncName(func_name){
  func_name_proper = func_name.substr('function '.length);
  func_name_proper = func_name_proper.substr(0, func_name_proper.indexOf('('));
  return func_name_proper;
 }

 /*
 *  Dec 9th:
 *   i.) Url Modified for 'urlManager' = TRUE; ie 'castor' 
 */  
 function wikkensUpdate(data){
  var func_name = arguments.callee.toString();
  func_name_now = getFuncName(func_name);
  //alert("successhandler, func_name_now: " + func_name_now);
  //$.fn.highstockview.update('wikkens-grid', 'index.php?r=Chart/ChartView', func_name_now);   // NOTE: 2nd parameter is static..
  $.fn.highstockview.update('wikkens-grid', '/helios_official_ug_logg_lang_dbcache_hs/Chart/ChartView', func_name_now);   // NOTE: 2nd parameter is static..
 }
 
 function FiveMinUpdate(data){
  var func_name = arguments.callee.toString();
  func_name_now = getFuncName(func_name);
  //alert("successhandler, func_name_now: " + func_name_now);
  $.fn.highstockview.update('wikkens-grid', 'index.php?r=Chart/ChartView', func_name_now);   // NOTE: 2nd parameter is static..
 }
 
 
 function TenMinUpdate(data){
  var func_name = arguments.callee.toString();
  func_name_now = getFuncName(func_name);
  alert("successhandler, func_name_now: " + func_name_now);
  $.fn.highstockview.update('wikkens-grid', 'index.php?r=Chart/ChartView', func_name_now);   // NOTE: 2nd parameter is static..
 }
 
 
 function AutoUpdate(data){
  //alert("AutoUpdate, auto_poll is " + auto_poll);         //IMPORTANT: Dave, uncomment this line if you want to see debug messages when you start the polling, and stop the polling as well..
  var func_name = arguments.callee.toString();
  func_name_now = getFuncName(func_name);

  if(auto_poll == "wikkens")
  {
   $.fn.highstockview.update('wikkens-grid', 'index.php?r=Chart/ChartView', func_name_now);   // NOTE: 2nd parameter is static..
  }
     
 }
 
 function setPollStatus(prop){
  this.auto_poll = prop;
 }
 
 
 
</script>


<?php

echo CHtml::ajaxSubmitButton(
	'PlayBack',
	array('Chart/ServerTouch'),
	array(
    'success'=>'wikkensUpdate'
	)
);


/* //Dec9th.. Commented out for Castor; Abhinandan.
echo CHtml::ajaxSubmitButton(
	'Last 5min',
	array('Chart/ServerTouch'),
	array(
    'success'=>'FiveMinUpdate'
	)
);
*/

//'js:function(){ setInterval(AutoUpdate, 2000); }',


  // Dec9th.. Commented out for Castor; Abhinandan.
/*
echo CHtml::ajaxSubmitButton(
	'Auto Poll',
	array('Chart/ServerTouch'),
	array( 
    //'beforeSend' => 'js:function(){ $("#auto_poll").val( "yes" ); }', 
    'success'    => 'js:function(){
                    setPollStatus("wikkens");
                    
                    if( auto_poll == "wikkens" )
                    {
                     setInterval(AutoUpdate, 2000);
                    }
                    
                    
                    /*
                    if( $("#auto_poll").val() == "yes" )
                    {
                     alert("auto_poll is " + $("#auto_poll").val() );
                     setInterval(AutoUpdate, 2000);
                    }
                    */
    //}'
	//)
//);    //Dec9th: End of commenting out. -Abhinandan.


/*  //Dec9th.. Commented out for Castor; Abhinandan.
echo CHtml::ajaxSubmitButton(
	'Stop Poll',
	array('Chart/ServerTouch'),
	array(
    'data'    => 'js:$("#stop_poll").serialize() ', 
    'success' => 'js:function(message){
                      if(message = "stop_please"){
                       //alert("auto_poll is still " + auto_poll );
                       
                       setPollStatus("no");
                       
                       //alert("auto_poll is now " + auto_poll );
                      }
    }'
	)
);
*/



/* //Dec9th.. Commented out for Castor; Abhinandan.
echo CHtml::ajaxSubmitButton(
	'Poll Last 10 min',
	array('Chart/ServerTouch'),
	array(
    'success'=>'TenMinUpdate'
	)
);
*/


?>
</div>

<?php echo CHtml::endForm(); ?>
</div><!-- form -->


<?php
 //$this->fb( $dataProvider->getData() );
  
 $this->Widget('ext.ActiveHighstock.HighstockWidget', array(
                                                       'id'           => 'wikkens-grid',
                                                       'dataProvider' => $dataProvider,
                                                       'options'      => array(
                                                                          'theme'         => 'grid',            //dark-blue dark-green gray grid skies
                                                                          
                                                                          //Below is the original code..                                                   
                                                                          //'rangeSelector' => array(
                                                                          //                    'selected' => 1
                                                                          //),
                                                                          
                                                                          
                                                                          //'selected' => buttonindex (upon page initial page load)..
                                                                          //Take from the jsFiddle..
                                                                          'rangeSelector' => array(
                                                                                              'buttons' => array(
                                                                                                             0 => array(
                                                                                                                    'count' => 1,
                                                                                                                    'type'  => 'minute',
                                                                                                                    'text'  => '1M'
                                                                                                             ),
                                                                                                             
                                                                                                             1 => array(
                                                                                                                    'count' => 5,
                                                                                                                    'type'  => 'minute',
                                                                                                                    'text'  => '5M'
                                                                                                             ),
                                                                                                             
                                                                                                             2 => array(
                                                                                                                    'type' => 'all',
                                                                                                                    'text' => 'All'
                                                                                                             )
                                                                                              ),
                                                                                              
                                                                                              'selected' => 2
                                                                          ),
                                                                          
                                                                          'credits'       => array(
                                                                                              'enabled' => false
                                                                          ),
                                                                          
                                                                          'title'         => array(
                                                                                              'text' => 'Helios Analyzer Data'
                                                                          ),
                                                                          
                                                                          'xAxis'         => array(
                                                                                              'maxZoom' => '4 * 3600000'  //4 hours
                                                                          ), 
                                                                           
                                                                          'yAxis'         => array(
                                                                                              'title' => array(
                                                                                                          'text' => 'DataValueTitle'
                                                                                              )
                                                                          ),
                                                                          
                                                                          'series'        => array(
                                                                                                         array(        //v..
                                                                                                          'name'         => 'SeriesTitle',      //title of data
                                                                                                          'dataResource' => 'data',     //data resource according to MySQL column 'data'
                                                                                                          'dateResource' => 'time',     //data resource according to MySQL column 'time'
                                                                                               )
                                                                          )
                                                       )
    ));
 
?>


