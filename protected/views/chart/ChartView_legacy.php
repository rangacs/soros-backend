<?php
/* @var $this ChartController */

$this->breadcrumbs=array(
	'Chart'=>array('/chart'),
	'Index',
);
?>

 <h1>
  <?php 
   echo $this->id;
  ?>
 </h1>

 <p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
 </p>


 <!-- Nov10th -->
 <div class="form">
<?php echo CHtml::beginForm(); ?>

<div class="row">
<?php echo CHtml::label('Information Notify:', 'some_text'); ?>
<?php echo CHtml::hiddenField( 'time_start', date('H:i:s') ); ?>
<?php echo CHtml::hiddenField( 'status_A', 'pollset' ); ?>            <!-- Make 'CHtml::textField' for debugging purposes -->
<?php echo CHtml::hiddenField( 'status_B', 'fetchset' ); ?>           <!-- "" -->
<?php echo CHtml::hiddenField( 'hidVal', '' ); ?>


<script type="text/javascript">
   $("#hidVal").val( $("#status_B").serialize() + "&" + $("#status_A").serialize() );
</script>

<?php
//Nov12th: LEFT OFF HERE!!! ... DONE ..jdr.. (see script tags above..)...

//Dave, the combining of specific post parameters works ok..
//Now, we have to figure out a way to overcome our timing problem..
 //SO.. Using firebug:
 //i.) That is, you click the ajax submit button, and the post variable is not set.. BUT
 //ii.) If you click the ajax submit button again, then you see the post variables ...

echo CHtml::ajaxSubmitButton(
	'Recent Info',
	array('Chart/ChartHandler'),
	array(
		'update'=>'#output',
    'beforeSend'=>'js:function(){  
                        alert( $("#hidVal").val() ); 
                  }',
    'data'=>'js:$("#hidVal").serialize()',
    'success'=>'js:function(){ 
      $("#yt1").attr("disabled","disabled");
      $("#yt0").removeAttr("disabled");
    }'
	)
);
?>
</div>

<?php echo CHtml::endForm(); ?>
</div><!-- form -->


<!-- <input type="submit" id="poll" value="Poll" /> -->
<!-- <input type="submit" id="fetch" value="Fetch" /> -->
<span id="output"></span>
<script type="text/javascript">
/*
 var status_A = $("#status_A").serialize();
 var status_B = $("#status_B").serialize();
 
 $("#poll").live('click', function(){
  var time_start = Math.round(new Date().getTime() / 1000);
  $.ajax({
          type:"POST",
          url:"index.php?r=Chart/ChartHandler",
          beforeSend:function(){ 
                          $("#poll").attr("disabled","disabled");
                          $("#fetch").removeAttr("disabled"); 
          }, 
          data:{status_A:status_A, time_start:time_start},
          success:function(message){
                       $("#output").empty();
                       $("#output").append(message);
          }
  })
 }); 
 
 
 $("#fetch").live('click', function(){
  var time_start = Math.round(new Date().getTime() / 1000);
  $.ajax({
          type:"POST",
          url:"index.php?r=Chart/ChartHandler",
          beforeSend:function(){ 
                          $("#fetch").attr("disabled","disabled");
                          $("#poll").removeAttr("disabled"); 
          },
          data:{status_B:status_B, time_start:time_start},
          success:function(message){
                       $("#output").empty();
                       $("#output").append(message);
          }
  })
 });
 */
</script>



<?php
 $this->Widget('ext.ActiveHighstock.HighstockWidget', array(
        'dataProvider'=>$dataProvider,
        'options'=> array(
            'theme' => 'grid', //dark-blue dark-green gray grid skies
        'rangeSelector'=>array('selected'=>1),
            'credits' => array('enabled' => false),
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
 
?>



