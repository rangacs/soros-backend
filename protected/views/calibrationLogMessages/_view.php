<?php
/* @var $this CalibrationLogMessagesController */
/* @var $data CalibrationLogMessages */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('cal_msg_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->cal_msg_id), array('view', 'id'=>$data->cal_msg_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('auto_calib_id')); ?>:</b>
	<?php echo CHtml::encode($data->auto_calib_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('error_type')); ?>:</b>
	<?php echo CHtml::encode($data->error_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_at')); ?>:</b>
	<?php echo CHtml::encode($data->updated_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_by')); ?>:</b>
	<?php echo CHtml::encode($data->updated_by); ?>
	<br />


</div>