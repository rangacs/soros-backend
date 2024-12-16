<?php
/* @var $this RfidCalMapController */
/* @var $data RfidCalMap */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_acid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->wcl_acid), array('view', 'id'=>$data->wcl_acid)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_item_code')); ?>:</b>
	<?php echo CHtml::encode($data->wcl_item_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_item_namev')); ?>:</b>
	<?php echo CHtml::encode($data->wcl_item_namev); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_sabia_cal_name')); ?>:</b>
	<?php echo CHtml::encode($data->wcl_sabia_cal_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_updated')); ?>:</b>
	<?php echo CHtml::encode($data->wcl_updated); ?>
	<br />


</div>