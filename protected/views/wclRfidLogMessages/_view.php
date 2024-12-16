<?php
/* @var $this WclRfidLogMessagesController */
/* @var $data WclRfidLogMessages */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('logid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->logid), array('view', 'id'=>$data->logid)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('message_type')); ?>:</b>
	<?php echo CHtml::encode($data->message_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('short_descrip')); ?>:</b>
	<?php echo CHtml::encode($data->short_descrip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('long_descrip')); ?>:</b>
	<?php echo CHtml::encode($data->long_descrip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('trip_id')); ?>:</b>
	<?php echo CHtml::encode($data->trip_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('unloaderID')); ?>:</b>
	<?php echo CHtml::encode($data->unloaderID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vehNo')); ?>:</b>
	<?php echo CHtml::encode($data->vehNo); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('flag')); ?>:</b>
	<?php echo CHtml::encode($data->flag); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('timestamp')); ?>:</b>
	<?php echo CHtml::encode($data->timestamp); ?>
	<br />

	*/ ?>

</div>