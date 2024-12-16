<?php
/* @var $this RtaDerivedConfigController */
/* @var $data RtaDerivedConfig */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('rta_ID_derived')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->rta_ID_derived), array('view', 'id'=>$data->rta_ID_derived)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rtaMasterID')); ?>:</b>
	<?php echo CHtml::encode($data->rtaMasterID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('data_source_rtaMasterID')); ?>:</b>
	<?php echo CHtml::encode($data->data_source_rtaMasterID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('filter_type')); ?>:</b>
	<?php echo CHtml::encode($data->filter_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('moving_avg_filter_sample_timespan')); ?>:</b>
	<?php echo CHtml::encode($data->moving_avg_filter_sample_timespan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kalman_filter_sample_timespan')); ?>:</b>
	<?php echo CHtml::encode($data->kalman_filter_sample_timespan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('massflowWeight_derivedCfg')); ?>:</b>
	<?php echo CHtml::encode($data->massflowWeight_derivedCfg); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('goodDataSecondsWeight_derivedCfg')); ?>:</b>
	<?php echo CHtml::encode($data->goodDataSecondsWeight_derivedCfg); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('percentageGoodDataRequired')); ?>:</b>
	<?php echo CHtml::encode($data->percentageGoodDataRequired); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kalman_gain_Q')); ?>:</b>
	<?php echo CHtml::encode($data->kalman_gain_Q); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kalman_gain_R')); ?>:</b>
	<?php echo CHtml::encode($data->kalman_gain_R); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('source_decay_comp')); ?>:</b>
	<?php echo CHtml::encode($data->source_decay_comp); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('source_decay_ref_date')); ?>:</b>
	<?php echo CHtml::encode($data->source_decay_ref_date); ?>
	<br />

	*/ ?>

</div>