<div id="chart_big"></div>
<?php 
$x = array();
$y = array();
for($i=0;$i<50; $i++)
{
	$x[] = $i;
	$y[] = rand(10, 200);
}

$x_data = json_encode($x);
$y_data = json_encode($y);

Yii::app()->clientScript->registerScript('chart_big',"
$(document).ready(function(){
        $.jqplot.config.enablePlugins = true;
        var s1 = $y_data;
        var ticks = $x_data;
         
        plot1 = $.jqplot('chart_big', [s1], {
            // Only animate if we're not using excanvas (not in IE 7 or IE 8)..
            animate: !$.jqplot.use_excanvas,
            seriesDefaults:{
                renderer:$.jqplot.BarRenderer,
                pointLabels: { show: true }
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    ticks: ticks
                }
            },
            highlighter: { show: false }
        });
     
        $('#chart1').bind('jqplotDataClick',
            function (ev, seriesIndex, pointIndex, data) {
                $('#info1').html('series: '+seriesIndex+', point: '+pointIndex+', data: '+data);
            }
        );
    });
");

?>
