<?php
/* @var $this TagQueuedController */
/* @var $data TagQueued */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('tagID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->tagID), array('view', 'id'=>$data->tagID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rtaMasterID')); ?>:</b>
	<?php echo CHtml::encode($data->rtaMasterID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tagName')); ?>:</b>
	<?php echo CHtml::encode($data->tagName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tagGroupID')); ?>:</b>
	<?php echo CHtml::encode($data->tagGroupID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('LocalstartTime')); ?>:</b>
	<?php echo CHtml::encode($data->LocalstartTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('LocalendTime')); ?>:</b>
	<?php echo CHtml::encode($data->LocalendTime); ?>
	<br />


</div>