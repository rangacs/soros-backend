<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('tagGroupID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->tagGroupID), array('view', 'id'=>$data->tagGroupID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tagGroupName')); ?>:</b>
	<?php echo CHtml::encode($data->tagGroupName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rtaMasterID')); ?>:</b>
	<?php echo CHtml::encode($data->rtaMasterID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('massflowWeight')); ?>:</b>
	<?php echo CHtml::encode($data->massflowWeight); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('goodDataSecondsWeight')); ?>:</b>
	<?php echo CHtml::encode($data->goodDataSecondsWeight); ?>
	<br />


</div>