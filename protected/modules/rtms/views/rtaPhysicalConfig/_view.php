<?php
/* @var $this RtaPhysicalConfigController */
/* @var $data RtaPhysicalConfig */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('rta_ID_physical')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->rta_ID_physical), array('view', 'id'=>$data->rta_ID_physical)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rtaMasterID')); ?>:</b>
	<?php echo CHtml::encode($data->rtaMasterID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('IPaddress')); ?>:</b>
	<?php echo CHtml::encode($data->IPaddress); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('goodDataSecondsWeight_physicalCfg')); ?>:</b>
	<?php echo CHtml::encode($data->goodDataSecondsWeight_physicalCfg); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('massflowWeight_physicalCfg')); ?>:</b>
	<?php echo CHtml::encode($data->massflowWeight_physicalCfg); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('analysis_timespan')); ?>:</b>
	<?php echo CHtml::encode($data->analysis_timespan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('averaging_subinterval_secs')); ?>:</b>
	<?php echo CHtml::encode($data->averaging_subinterval_secs); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('detectorID')); ?>:</b>
	<?php echo CHtml::encode($data->detectorID); ?>
	<br />

	*/ ?>

</div>