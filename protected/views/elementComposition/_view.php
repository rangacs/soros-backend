<?php
/* @var $this ElementCompositionController */
/* @var $data ElementComposition */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('element_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->element_id), array('view', 'id'=>$data->element_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('source_id')); ?>:</b>
	<?php echo CHtml::encode($data->source_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('element_name')); ?>:</b>
	<?php echo CHtml::encode($data->element_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('element_value')); ?>:</b>
	<?php echo CHtml::encode($data->element_value); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('element_type')); ?>:</b>
	<?php echo CHtml::encode($data->element_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('estimated_prob_error')); ?>:</b>
	<?php echo CHtml::encode($data->estimated_prob_error); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('estimated_max')); ?>:</b>
	<?php echo CHtml::encode($data->estimated_max); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('estimated_min')); ?>:</b>
	<?php echo CHtml::encode($data->estimated_min); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_timestamp')); ?>:</b>
	<?php echo CHtml::encode($data->update_timestamp); ?>
	<br />

	*/ ?>

</div>