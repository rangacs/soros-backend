<?php
/* @var $this ProductProfileController */
/* @var $data ProductProfile */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('product_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->product_id), array('view', 'id'=>$data->product_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('product_name')); ?>:</b>
	<?php echo CHtml::encode($data->product_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_on')); ?>:</b>
	<?php echo CHtml::encode($data->created_on); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_on')); ?>:</b>
	<?php echo CHtml::encode($data->updated_on); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('target_flow')); ?>:</b>
	<?php echo CHtml::encode($data->target_flow); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max_flow_deviation')); ?>:</b>
	<?php echo CHtml::encode($data->max_flow_deviation); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('estimate_lsq_mins')); ?>:</b>
	<?php echo CHtml::encode($data->estimate_lsq_mins); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sensitivity')); ?>:</b>
	<?php echo CHtml::encode($data->sensitivity); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('control_period_mins')); ?>:</b>
	<?php echo CHtml::encode($data->control_period_mins); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('actual_fpm')); ?>:</b>
	<?php echo CHtml::encode($data->actual_fpm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('actual_tph')); ?>:</b>
	<?php echo CHtml::encode($data->actual_tph); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('default_profile')); ?>:</b>
	<?php echo CHtml::encode($data->default_profile); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('comment')); ?>:</b>
	<?php echo CHtml::encode($data->comment); ?>
	<br />

	*/ ?>

</div>