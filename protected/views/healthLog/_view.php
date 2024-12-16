<?php
/* @var $this HealthLogController */
/* @var $data HealthLog */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_a_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->wcl_a_id), array('view', 'id'=>$data->wcl_a_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_plantCode')); ?>:</b>
	<?php echo CHtml::encode($data->wcl_plantCode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_unloaderId')); ?>:</b>
	<?php echo CHtml::encode($data->wcl_unloaderId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_healthStatus')); ?>:</b>
	<?php echo CHtml::encode($data->wcl_healthStatus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_auto_tag_id')); ?>:</b>
	<?php echo CHtml::encode($data->wcl_auto_tag_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_auto_tag_mCode')); ?>:</b>
	<?php echo CHtml::encode($data->wcl_auto_tag_mCode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_ash')); ?>:</b>
	<?php echo CHtml::encode($data->wcl_ash); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_moisture')); ?>:</b>
	<?php echo CHtml::encode($data->wcl_moisture); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_sulfur')); ?>:</b>
	<?php echo CHtml::encode($data->wcl_sulfur); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_gcv')); ?>:</b>
	<?php echo CHtml::encode($data->wcl_gcv); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_timestamp')); ?>:</b>
	<?php echo CHtml::encode($data->wcl_timestamp); ?>
	<br />

	*/ ?>

</div>