<?php
/* @var $this GadgetsDataController */
/* @var $data GadgetsData */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('gadget_data_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->gadget_data_id), array('view', 'id'=>$data->gadget_data_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gadget_type')); ?>:</b>
	<?php echo CHtml::encode($data->gadget_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lay_id')); ?>:</b>
	<?php echo CHtml::encode($data->lay_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('widgetsPos')); ?>:</b>
	<?php echo CHtml::encode($data->widgetsPos); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gadget_name')); ?>:</b>
	<?php echo CHtml::encode($data->gadget_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gadget_size')); ?>:</b>
	<?php echo CHtml::encode($data->gadget_size); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_updated')); ?>:</b>
	<?php echo CHtml::encode($data->last_updated); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('data_source')); ?>:</b>
	<?php echo CHtml::encode($data->data_source); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('detector_source')); ?>:</b>
	<?php echo CHtml::encode($data->detector_source); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('group_style')); ?>:</b>
	<?php echo CHtml::encode($data->group_style); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('display_style')); ?>:</b>
	<?php echo CHtml::encode($data->display_style); ?>
	<br />

	*/ ?>

</div>