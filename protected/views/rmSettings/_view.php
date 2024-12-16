<?php
/* @var $this RmSettingsController */
/* @var $data RmSettings */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('varName')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->varName), array('view', 'id'=>$data->varName)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('varKey')); ?>:</b>
	<?php echo CHtml::encode($data->varKey); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('varValue')); ?>:</b>
	<?php echo CHtml::encode($data->varValue); ?>
	<br />


</div>