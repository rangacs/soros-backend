<?php

$model = $data;
$impString = implode('', $model);

if ('1969-12-31 18:00:00' === $model['EndTime'])
    return;
$spList = array('LSF', 'KH', 'SM', 'IM', 'AM');
// Filtering empty row
if (strlen($impString) < 20) {

    return;
}
$endDate = date('Y-m-d H:i:s', strtotime($model['EndTime']) - $fallbackTime);
$startDate = date('Y-m-d H:i:s', strtotime($endDate) - $samplingTime);


$labTime = date("Y-m-d H:i:s", strtotime($model['EndTime']));

echo "<tr> <td>" . $labTime . "</td>";

//Lab data cell blocks
foreach ($displayElement as $ele):
    echo " <td style='background-color:#bde5f9;'>" . round($model[$ele], 2) . "</td>";
endforeach;

//remove 
$analysisRow = isset($analysisAvg->analysisAvg[$labTime]) ? $analysisAvg->analysisAvg[$labTime] : array() ;


if(empty($analysisRow)){
    
//Lab data cell blocks
foreach ($displayElement as $ele):
    echo " <td style='background-color:#ededfb;'>0</td>";
endforeach;

//Repete lab columns becouse no analysis columns present


foreach ($displayElement as $ele):
    echo " <td  class='font-red warning'> - </td>";
endforeach;


    echo "<td> <input class='' type='checkbox' disabled='disabled' name='[]' value='$labTime'/></td>";
return;
    
}

$formAvg = CustomAverage::getAverage($analysisRow, $spList);

unset($analysisRow['LSF']);
unset($analysisRow['C3S']);
unset($analysisRow['SM']);
unset($analysisRow['AM']);
unset($analysisRow['IM']);
unset($analysisRow['KH']);
$anAverage = array_merge($formAvg, $analysisRow);


//Analysis result cell blocks
foreach ($displayElement as $key => $ele):

    $eName = trim($ele);
	if(!isset($anAverage[$ele])){
		
		//  $eleStyle = $difference != 0 ? 'font-red  warning' :  '';
		echo "<td class=''>-</td>";
		continue;
	}
	
	
    if (isset($anAverage[$ele])) {
		
		 $avg = round($anAverage[$ele], 2);

        
    }else{
		$avg = '0';
		
	}


    echo " <td>" . $avg . " </td>";
endforeach;



foreach ($displayElement as $key => $ele):

    $eName = trim($ele);
	if(!isset($anAverage[$ele])){
		
		//  $eleStyle = $difference != 0 ? 'font-red  warning' :  '';
		echo "<td class='font-red  warning'>-</td>";
		continue;
	}

    $avg = '-';//round($anAverage[$ele], 2);
	
	$eleStyle = '';

    if (isset($anAverage[$ele])) {
		
		 $avg = round($anAverage[$ele], 2);

            $difference = round($model[$ele] - $avg, 2);
            
        if ($model[$ele] != 0 && $avg != 0) {

            $eleStyle = $difference != 0 ? 'font-red  warning' :  '';
        } else {

              $eleStyle = $difference != 0 ? 'font-red  warning' :  '';
            $difference = '-'; 
        }
    } else {
          $eleStyle = $difference != 0 ? 'font-red  warning' :  '';
        $difference = '-';
    }
	
	$eleStyle = 'font-red warning';
	$oddGrey = '';

    echo " <td style='$oddGrey' class='{$eleStyle}' >$difference </td>";
endforeach;




if (isset($anAverage['CaO'])) {

    echo "<td> <input class='lab-time' type='checkbox' name='labtime[]' value='$labTime'/></td>";
} else {


    echo "<td> <input class='' type='checkbox' disabled='disabled' name='[]' value='$labTime'/></td>";
}
echo "</tr>";
?>




