<?php
/* @var $this CalibrationLogController */
/* @var $data CalibrationLog */

$cid 	= $data['cal_id'];
$query  = "select * from calibration_log where  cal_id < ".$cid." order by updated desc , cal_id desc limit 1";

//var_dump($query);
$prevResult = Yii::app()->db->createCommand($query)->queryRow();
$calibEle = array('SiO2','Al2O3','Fe2O3','CaO');

?>
<tr>


<?php 

    $td =  '<td class="">'.$data['updated'].'</td>'. '<td class="">'.$data['run_type'].'</td>';
	
	echo $td;
	 
?>


<?php 

	foreach($calibEle as $ele){
	$pva    = (float)$prevResult[$ele.'_offset']; 
	$cva    = (float)$data[$ele.'_offset'];

	$pva    = (float)$prevResult[$ele.'_offset']; 
	$cva    = (float)$data[$ele.'_offset'];
	$td 	= $pva != $cva ? '<td class="font-red warning">'.$data[$ele.'_offset'].'</td>' :  '<td class="">'.$data[$ele.'_offset'].'</td>';
	echo 	$td;
	 
		
	}

?>

</tr>

