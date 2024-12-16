<?php
/* @var $this AcSettingsController */
/* @var $data AcSettings */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ac_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ac_id), array('view', 'id'=>$data->ac_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('element_name')); ?>:</b>
	<?php echo CHtml::encode($data->element_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('min')); ?>:</b>
	<?php echo CHtml::encode($data->min); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max')); ?>:</b>
	<?php echo CHtml::encode($data->max); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('diff')); ?>:</b>
	<?php echo CHtml::encode($data->diff); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max_offset_change')); ?>:</b>
	<?php echo CHtml::encode($data->max_offset_change); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('correction_pct')); ?>:</b>
	<?php echo CHtml::encode($data->correction_pct); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('last_updated')); ?>:</b>
	<?php echo CHtml::encode($data->last_updated); ?>
	<br />

	*/ ?>

</div>