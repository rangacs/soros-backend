<?php
/* @var $this RtaAverageGroupidsController */
/* @var $data RtaAverageGroupids */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('rtaMasterID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->rtaMasterID), array('view', 'id'=>$data->rtaMasterID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('recordID')); ?>:</b>
	<?php echo CHtml::encode($data->recordID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('groupAveragingID')); ?>:</b>
	<?php echo CHtml::encode($data->groupAveragingID); ?>
	<br />


</div>