<?php
/* @var $this RtaJobTableController */
/* @var $data RtaJobTable */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('jobID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->jobID), array('view', 'id'=>$data->jobID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('jobStatus')); ?>:</b>
	<?php echo CHtml::encode($data->jobStatus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('linuxPID')); ?>:</b>
	<?php echo CHtml::encode($data->linuxPID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_time')); ?>:</b>
	<?php echo CHtml::encode($data->start_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end_time')); ?>:</b>
	<?php echo CHtml::encode($data->end_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('backupTable')); ?>:</b>
	<?php echo CHtml::encode($data->backupTable); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tempTable')); ?>:</b>
	<?php echo CHtml::encode($data->tempTable); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('regenTable')); ?>:</b>
	<?php echo CHtml::encode($data->regenTable); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('originalTable')); ?>:</b>
	<?php echo CHtml::encode($data->originalTable); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('originalTableID')); ?>:</b>
	<?php echo CHtml::encode($data->originalTableID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('loopsFinished')); ?>:</b>
	<?php echo CHtml::encode($data->loopsFinished); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('recordsRemaining')); ?>:</b>
	<?php echo CHtml::encode($data->recordsRemaining); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('recordsTotal')); ?>:</b>
	<?php echo CHtml::encode($data->recordsTotal); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('maxID')); ?>:</b>
	<?php echo CHtml::encode($data->maxID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dateAdded')); ?>:</b>
	<?php echo CHtml::encode($data->dateAdded); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dateModified')); ?>:</b>
	<?php echo CHtml::encode($data->dateModified); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dateCompleted')); ?>:</b>
	<?php echo CHtml::encode($data->dateCompleted); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userAdded')); ?>:</b>
	<?php echo CHtml::encode($data->userAdded); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userModified')); ?>:</b>
	<?php echo CHtml::encode($data->userModified); ?>
	<br />

	*/ ?>

</div>