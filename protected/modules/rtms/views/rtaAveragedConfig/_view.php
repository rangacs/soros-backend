<?php
/* @var $this RtaAveragedConfigController */
/* @var $data RtaAveragedConfig */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('rta_ID_averaged')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->rta_ID_averaged), array('view', 'id'=>$data->rta_ID_averaged)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rtaMasterID')); ?>:</b>
	<?php echo CHtml::encode($data->rtaMasterID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('groupAveragingID')); ?>:</b>
	<?php echo CHtml::encode($data->groupAveragingID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('massflowWeight_averagedCfg')); ?>:</b>
	<?php echo CHtml::encode($data->massflowWeight_averagedCfg); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('goodDataSecondsWeight_averagedCfg')); ?>:</b>
	<?php echo CHtml::encode($data->goodDataSecondsWeight_averagedCfg); ?>
	<br />


</div>