<?php
/* @var $this RtdShiftTimesController */
/* @var $data RtdShiftTimes */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('shiftTimeID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->shiftTimeID), array('view', 'id'=>$data->shiftTimeID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shiftStart1')); ?>:</b>
	<?php echo CHtml::encode($data->shiftStart1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shiftDuration1')); ?>:</b>
	<?php echo CHtml::encode($data->shiftDuration1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shiftStart2')); ?>:</b>
	<?php echo CHtml::encode($data->shiftStart2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shiftDuration2')); ?>:</b>
	<?php echo CHtml::encode($data->shiftDuration2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shiftStart3')); ?>:</b>
	<?php echo CHtml::encode($data->shiftStart3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shiftDuration3')); ?>:</b>
	<?php echo CHtml::encode($data->shiftDuration3); ?>
	<br />


</div>