<?php
$rmsquery = "select * from rm_settings where varKey = 'SOROS_DISPLAY_ELEMENTS' ";
$result = Yii::app()->db->createCommand($rmsquery)->queryRow();
if ($result && $result['varValue']) {
    $showCaseElements = $result['varValue'];
}
?>
<table class="list-table">
    <thead>
        <tr>
            <th class="ui-state-default ui-corner-top">Tag Name</th>
            <th class="ui-state-default ui-corner-top">Start Time</th>
            <th class="ui-state-default ui-corner-top">End Time</th>
            <!--<th>Status</th>-->
            <?php
              
                foreach(explode(",",$showCaseElements) as $ele => $key){
                    echo '<th class="ui-state-default ui-corner-top">'.$key.'</th>';
                }
            ?>
        </tr>
    </thead>
    <tbody>
<?php
//        var_dump($tagList);
foreach ($tagList as $tag) {
    echo "<tr>";
    echo "<td>" . $tag["tagName"] . "</td>";
    echo "<td>" . $tag["LocalstartTime"] . "</td>";
    echo "<td>" . $tag["LocalendTime"] . "</td>";
    
                foreach(explode(",",$showCaseElements) as $ele){
                    
                    $data  = AnalysisDataProvider::queryAvg(explode(",",$showCaseElements), $tag["LocalstartTime"], $tag["LocalendTime"]);
                    echo "<td>".$data[$ele]."</td>";
                }
//            echo "<td>".$tag["status"]."</td>";
}
?>
    </tbody>

</table>
