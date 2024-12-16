<?php
/* @var $this SourceController */
/* @var $data Source */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('src_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->src_id), array('view', 'id'=>$data->src_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('src_name')); ?>:</b>
	<?php echo CHtml::encode($data->src_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('src_type')); ?>:</b>
	<?php echo CHtml::encode($data->src_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('product_id')); ?>:</b>
	<?php echo CHtml::encode($data->product_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('src_priority')); ?>:</b>
	<?php echo CHtml::encode($data->src_priority); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('src_distance')); ?>:</b>
	<?php echo CHtml::encode($data->src_distance); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('src_delay')); ?>:</b>
	<?php echo CHtml::encode($data->src_delay); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('src_min_feedrate')); ?>:</b>
	<?php echo CHtml::encode($data->src_min_feedrate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('src_max_feedrate')); ?>:</b>
	<?php echo CHtml::encode($data->src_max_feedrate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('src_proposed_feedrate')); ?>:</b>
	<?php echo CHtml::encode($data->src_proposed_feedrate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('src_measured_feedrate')); ?>:</b>
	<?php echo CHtml::encode($data->src_measured_feedrate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('src_cost')); ?>:</b>
	<?php echo CHtml::encode($data->src_cost); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('src_status')); ?>:</b>
	<?php echo CHtml::encode($data->src_status); ?>
	<br />

	*/ ?>

</div>