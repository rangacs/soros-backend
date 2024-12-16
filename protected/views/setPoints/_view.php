<?php
/* @var $this SetPointsController */
/* @var $data SetPoints */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->sp_id), array('view', 'id'=>$data->sp_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('product_id')); ?>:</b>
	<?php echo CHtml::encode($data->product_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_name')); ?>:</b>
	<?php echo CHtml::encode($data->sp_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_value_num')); ?>:</b>
	<?php echo CHtml::encode($data->sp_value_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_value_den')); ?>:</b>
	<?php echo CHtml::encode($data->sp_value_den); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_const_value_num')); ?>:</b>
	<?php echo CHtml::encode($data->sp_const_value_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_const_value_den')); ?>:</b>
	<?php echo CHtml::encode($data->sp_const_value_den); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_tolerance_ulevel')); ?>:</b>
	<?php echo CHtml::encode($data->sp_tolerance_ulevel); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_tolerance_llevel')); ?>:</b>
	<?php echo CHtml::encode($data->sp_tolerance_llevel); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_weight')); ?>:</b>
	<?php echo CHtml::encode($data->sp_weight); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_status')); ?>:</b>
	<?php echo CHtml::encode($data->sp_status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_priority')); ?>:</b>
	<?php echo CHtml::encode($data->sp_priority); ?>
	<br />

	*/ ?>

</div>