<?php
/* @var $this RfidCalTolSettingsController */
/* @var $data RfidCalTolSettings */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_cal_tol_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->wcl_cal_tol_id), array('view', 'id'=>$data->wcl_cal_tol_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wcl_cal_tol_item_code')); ?>:</b>
	<?php echo CHtml::encode($data->wcl_cal_tol_item_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Moisture')); ?>:</b>
	<?php echo CHtml::encode($data->Moisture); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Moisture_Tol')); ?>:</b>
	<?php echo CHtml::encode($data->Moisture_Tol); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Ash')); ?>:</b>
	<?php echo CHtml::encode($data->Ash); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Ash_Tol')); ?>:</b>
	<?php echo CHtml::encode($data->Ash_Tol); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Sulfur')); ?>:</b>
	<?php echo CHtml::encode($data->Sulfur); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('Sulfur_Tol')); ?>:</b>
	<?php echo CHtml::encode($data->Sulfur_Tol); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('GCV')); ?>:</b>
	<?php echo CHtml::encode($data->GCV); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('GCV_Tol')); ?>:</b>
	<?php echo CHtml::encode($data->GCV_Tol); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('BTU')); ?>:</b>
	<?php echo CHtml::encode($data->BTU); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('BTU_Tol')); ?>:</b>
	<?php echo CHtml::encode($data->BTU_Tol); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_on')); ?>:</b>
	<?php echo CHtml::encode($data->updated_on); ?>
	<br />

	*/ ?>

</div>